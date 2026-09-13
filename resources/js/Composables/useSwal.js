import Swal from 'sweetalert2';

export const useSwal = () => {
    const toast = (message, type = 'success') => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    };

    const confirm = (message = 'Are you sure?', title = 'Confirm') => {
        return Swal.fire({
            title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
        });
    };

    const success = (message) => toast(message, 'success');
    const error = (message) => toast(message, 'error');
    const info = (message) => toast(message, 'info');

    return { toast, confirm, success, error, info };
};
