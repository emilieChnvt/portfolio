document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.openModal').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const modal = document.querySelector(`.modal[data-id="${id}"]`);
            modal.classList.remove('hidden');
        });
    });

    document.querySelectorAll('.closeModal').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const modal = document.querySelector(`.modal[data-id="${id}"]`);
            modal.classList.add('hidden');
        });
    });



});
