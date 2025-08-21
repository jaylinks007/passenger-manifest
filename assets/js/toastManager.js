// Simple Toast Manager
const toastManager = {
    showToast: function(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.style.position = 'fixed';
            container.style.top = '20px';
            container.style.right = '20px';
            container.style.zIndex = '1050';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast-message ${type}`;
        toast.textContent = message;

        document.getElementById('toast-container').appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
};

const style = document.createElement('style');
style.innerHTML = `
#toast-container {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
.toast-message {
    background-color: #28a745;
    color: white;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    opacity: 0.9;
    transition: opacity 0.3s;
}
.toast-message.error {
    background-color: #dc3545;
}
.toast-message.info {
    background-color: #17a2b8;
}
`;
document.head.appendChild(style);
