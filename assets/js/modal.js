//https://www.w3schools.com/howto/howto_css_modals.asp
const buttons = document.querySelectorAll('.modal-btn');
buttons.forEach(function(btn) {
    btn.onclick = function() {
        openModal(btn.dataset.modal)
    };
});

const closeBtns = document.querySelectorAll('.modal-close');
closeBtns.forEach(function(el) {
    el.onclick = closeModal;
})
function openModal(modal) {
    let el = document.querySelector('.modal[data-modal="'+modal+'"]');
    el.classList.add('active');
    document.querySelector('.modal[data-modal="'+modal+'"] .modal-background').onclick = closeModal;
}
function closeModal() {
    let el = document.querySelector('.modal.active');
    el.classList.remove('active');
}