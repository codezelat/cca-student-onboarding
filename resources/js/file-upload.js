/**
 * File Upload Handler with Real-time Progress Tracking
 * Handles direct uploads to Cloudflare R2 with progress indicators
 */

class FileUploadHandler {
    constructor() {
        this.uploadedFiles = new Map(); // Track uploaded files by field name
        this.uploadQueue = [];
        this.isUploading = false;
        this.csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content;
    }

    /**
     * Upload a single file with progress tracking
     * @param {File} file - The file to upload
     * @param {string} fieldName - Form field name
     * @param {string} directory - R2 directory path
     * @param {Function} onProgress - Progress callback
     * @returns {Promise}
     */
    async uploadFile(file, fieldName, directory, onProgress = null) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append("file", file);
            formData.append("field_name", fieldName);
            formData.append("directory", directory);

            const xhr = new XMLHttpRequest();

            // Track upload progress
            xhr.upload.addEventListener("progress", (e) => {
                if (e.lengthComputable) {
                    const percentComplete = Math.round(
                        (e.loaded / e.total) * 100
                    );
                    if (onProgress) {
                        onProgress(percentComplete, e.loaded, e.total);
                    }
                }
            });

            // Handle completion
            xhr.addEventListener("load", () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Store uploaded file data
                            this.uploadedFiles.set(
                                fieldName,
                                response.data.file
                            );
                            resolve(response.data.file);
                        } else {
                            reject(
                                new Error(response.message || "Upload failed")
                            );
                        }
                    } catch (error) {
                        reject(new Error("Failed to parse server response"));
                    }
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        reject(
                            new Error(
                                response.message ||
                                    `Upload failed with status ${xhr.status}`
                            )
                        );
                    } catch (error) {
                        reject(
                            new Error(`Upload failed with status ${xhr.status}`)
                        );
                    }
                }
            });

            // Handle errors
            xhr.addEventListener("error", () => {
                reject(new Error("Network error during upload"));
            });

            xhr.addEventListener("abort", () => {
                reject(new Error("Upload cancelled"));
            });

            // Send request
            xhr.open("POST", "/api/upload-file");
            xhr.setRequestHeader("X-CSRF-TOKEN", this.csrfToken);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.send(formData);
        });
    }

    /**
     * Delete an uploaded file
     * @param {string} filePath - Path to file in R2
     * @returns {Promise}
     */
    async deleteFile(filePath) {
        const response = await fetch("/api/delete-file", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": this.csrfToken,
                Accept: "application/json",
            },
            body: JSON.stringify({ path: filePath }),
        });

        if (!response.ok) {
            throw new Error("Failed to delete file");
        }

        return await response.json();
    }

    /**
     * Get uploaded file data for a field
     * @param {string} fieldName
     * @returns {Object|null}
     */
    getUploadedFile(fieldName) {
        return this.uploadedFiles.get(fieldName) || null;
    }

    /**
     * Clear all uploaded files from memory
     */
    clearUploadedFiles() {
        this.uploadedFiles.clear();
    }

    /**
     * Format file size to human readable format
     * @param {number} bytes
     * @returns {string}
     */
    formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return (
            Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i]
        );
    }

    /**
     * Validate file before upload
     * @param {File} file
     * @param {Object} rules
     * @returns {Object}
     */
    validateFile(file, rules = {}) {
        const errors = [];
        const maxSize = rules.maxSize || 10 * 1024 * 1024; // 10MB default

        if (file.size > maxSize) {
            errors.push(
                `File size (${this.formatFileSize(
                    file.size
                )}) exceeds maximum allowed size (${this.formatFileSize(
                    maxSize
                )})`
            );
        }

        if (rules.allowedTypes && rules.allowedTypes.length > 0) {
            const fileType = file.type.toLowerCase();
            const isAllowed = rules.allowedTypes.some((type) => {
                if (type.includes("*")) {
                    // Handle wildcard types like 'image/*'
                    const prefix = type.split("/")[0];
                    return fileType.startsWith(prefix + "/");
                }
                return fileType === type;
            });

            if (!isAllowed) {
                errors.push(
                    `File type "${
                        file.type
                    }" is not allowed. Allowed types: ${rules.allowedTypes.join(
                        ", "
                    )}`
                );
            }
        }

        return {
            valid: errors.length === 0,
            errors: errors,
        };
    }
}

// Create global instance
window.fileUploadHandler = new FileUploadHandler();

// Export for module use
export default FileUploadHandler;
