// Modal functionality for Medi-Care Health Information System

class Modal {
    constructor(modalId) {
        this.modal = document.getElementById(modalId);
        this.isOpen = false;
        this.init();
    }
    
    init() {
        if (!this.modal) return;
        
        // Close button
        const closeBtn = this.modal.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }
        
        // Close on outside click
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });
        
        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
    }
    
    open() {
        if (this.modal) {
            this.modal.style.display = 'block';
            this.isOpen = true;
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
    }
    
    close() {
        if (this.modal) {
            this.modal.style.display = 'none';
            this.isOpen = false;
            document.body.style.overflow = 'auto'; // Restore scrolling
        }
    }
    
    setContent(title, body) {
        const titleElement = this.modal.querySelector('.modal-title');
        const bodyElement = this.modal.querySelector('.modal-body');
        
        if (titleElement) titleElement.textContent = title;
        if (bodyElement) bodyElement.innerHTML = body;
    }
}

// Confirmation modal
class ConfirmModal extends Modal {
    constructor(modalId) {
        super(modalId);
        this.callback = null;
    }
    
    show(message, callback) {
        this.callback = callback;
        this.setContent('Confirm Action', `
            <p>${message}</p>
            <div class="modal-actions">
                <button class="btn btn-danger" onclick="confirmModal.confirm()">Confirm</button>
                <button class="btn" onclick="confirmModal.close()">Cancel</button>
            </div>
        `);
        this.open();
    }
    
    confirm() {
        if (this.callback) {
            this.callback();
        }
        this.close();
    }
}

// Initialize modals when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Create global modal instances
    window.confirmModal = new ConfirmModal('confirmModal');
    
    // Initialize all modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        new Modal(modal.id);
    });
});

// Helper function to show confirmation dialog
function showConfirm(message, callback) {
    if (window.confirmModal) {
        window.confirmModal.show(message, callback);
    } else {
        // Fallback to browser confirm
        if (confirm(message)) {
            callback();
        }
    }
}
