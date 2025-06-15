document.getElementById('openModal').addEventListener('click', function () {
    document.getElementById('modal').classList.remove('hidden');
});

document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('modal').classList.add('hidden');
});

// Optional: Close on background click
document.getElementById('modal').addEventListener('click', function (e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
