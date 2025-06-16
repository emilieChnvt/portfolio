document.addEventListener('DOMContentLoaded', () => {

    // Ouvrir la modale correspondante
    document.querySelectorAll('.openModal').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const modal = document.querySelector(`.modal[data-id="${id}"]`);
            modal.classList.remove('hidden');
        });
    });

    // Fermer la modale correspondante
    document.querySelectorAll('.closeModal').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const modal = document.querySelector(`.modal[data-id="${id}"]`);
            modal.classList.add('hidden');
        });
    });

    // Fermer la modale si clic en dehors
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });

});
