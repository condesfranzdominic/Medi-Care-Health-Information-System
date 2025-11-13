// Confirmation Modal System for Medi-Care
// Replaces all browser confirm() dialogs with custom modals

class ConfirmModal {
    constructor() {
        this.modal = null;
        this.callback = null;
        this.init();
    }
    
    init() {
        // Create modal HTML if it doesn't exist
        if (!document.getElementById('confirmModal')) {
            const modalHTML = `
                <div id="confirmModal" class="confirm-modal-overlay" style="display: none;">
                    <div class="confirm-modal-container">
                        <div class="confirm-modal-header">
                            <h3 class="confirm-modal-title">Confirm Action</h3>
                            <button type="button" class="confirm-modal-close" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="confirm-modal-body">
                            <div class="confirm-modal-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <p class="confirm-modal-message"></p>
                        </div>
                        <div class="confirm-modal-footer">
                            <button type="button" class="confirm-modal-btn confirm-modal-btn-cancel">Cancel</button>
                            <button type="button" class="confirm-modal-btn confirm-modal-btn-confirm">Confirm</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
        
        this.modal = document.getElementById('confirmModal');
        this.messageEl = this.modal.querySelector('.confirm-modal-message');
        this.titleEl = this.modal.querySelector('.confirm-modal-title');
        this.confirmBtn = this.modal.querySelector('.confirm-modal-btn-confirm');
        this.cancelBtn = this.modal.querySelector('.confirm-modal-btn-cancel');
        this.closeBtn = this.modal.querySelector('.confirm-modal-close');
        
        // Event listeners
        this.confirmBtn.addEventListener('click', () => this.handleConfirm());
        this.cancelBtn.addEventListener('click', () => this.handleCancel());
        this.closeBtn.addEventListener('click', () => this.handleCancel());
        
        // Close on overlay click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.handleCancel();
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.style.display !== 'none') {
                this.handleCancel();
            }
        });
    }
    
    show(message, title = 'Confirm Action', confirmText = 'Confirm', cancelText = 'Cancel', type = 'warning') {
        this.messageEl.textContent = message;
        this.titleEl.textContent = title;
        this.confirmBtn.textContent = confirmText;
        this.cancelBtn.textContent = cancelText;
        
        // Set icon based on type
        const iconEl = this.modal.querySelector('.confirm-modal-icon i');
        if (iconEl) {
            iconEl.className = 'fas ' + (type === 'danger' ? 'fa-exclamation-circle' : 
                                         type === 'info' ? 'fa-info-circle' : 
                                         'fa-exclamation-triangle');
        }
        
        // Set button color based on type
        this.confirmBtn.className = 'confirm-modal-btn confirm-modal-btn-confirm';
        if (type === 'danger') {
            this.confirmBtn.classList.add('confirm-modal-btn-danger');
        }
        
        this.modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Focus on cancel button for accessibility
        this.cancelBtn.focus();
    }
    
    handleConfirm() {
        if (this.callback) {
            this.callback();
        }
        this.close();
    }
    
    handleCancel() {
        this.close();
    }
    
    close() {
        this.modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        this.callback = null;
    }
    
    // Promise-based confirmation
    confirm(message, title, confirmText, cancelText, type) {
        return new Promise((resolve) => {
            this.callback = () => resolve(true);
            this.show(message, title, confirmText, cancelText, type);
            
            // Also handle cancel
            const originalCancel = this.handleCancel.bind(this);
            this.handleCancel = () => {
                resolve(false);
                originalCancel();
            };
        });
    }
}

// Global confirmation function that replaces window.confirm()
window.showConfirm = function(message, title, confirmText, cancelText, type) {
    return new Promise((resolve) => {
        if (!window.confirmModalInstance) {
            window.confirmModalInstance = new ConfirmModal();
        }
        
        window.confirmModalInstance.callback = () => resolve(true);
        window.confirmModalInstance.show(message, title, confirmText, cancelText, type);
        
        // Handle cancel
        const originalCancel = window.confirmModalInstance.handleCancel.bind(window.confirmModalInstance);
        window.confirmModalInstance.handleCancel = () => {
            resolve(false);
            originalCancel();
            window.confirmModalInstance.handleCancel = originalCancel;
        };
    });
};

// Helper function for form onsubmit handlers
window.handleDelete = function(event, message) {
    event.preventDefault();
    const form = event.target;
    
    showConfirm(
        message || 'Are you sure you want to delete this item?',
        'Confirm Delete',
        'Yes, Delete',
        'Cancel',
        'danger'
    ).then(confirmed => {
        if (confirmed) {
            form.submit();
        }
    });
    
    return false;
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    if (!window.confirmModalInstance) {
        window.confirmModalInstance = new ConfirmModal();
    }
});

