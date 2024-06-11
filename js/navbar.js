function navbar(){
    const toggleBtn = document.querySelector('.toggle_btn')
        const toggleBtnIcon = document.querySelector('.toggle_btn i')
        const dropDownMenu = document.querySelector('.dropdown_menu')
        const dropDownLinks = dropDownMenu.querySelectorAll('a');

        toggleBtn.onclick = function () {
            dropDownMenu.classList.toggle('open')
            const isOpen = dropDownMenu.classList.contains('open')
            toggleBtnIcon.classList = isOpen ? 'fas fa-times' : 'fa fa-bars menu-icon'
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth > 1043.20) {
                dropDownMenu.classList.remove('open');
                toggleBtnIcon.classList = 'fa fa-bars menu-icon';
            }
        });

        dropDownLinks.forEach(link => {
            link.addEventListener('click', function() {
                dropDownMenu.classList.remove('open');
                toggleBtnIcon.classList = 'fa fa-bars menu-icon';
            });
        });
               
}
/*Carga la funcion en la pagina*/
document.addEventListener('DOMContentLoaded', function() {
    navbar();
});

