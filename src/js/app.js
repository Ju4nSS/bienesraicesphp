document.addEventListener('DOMContentLoaded', function() {
    eventListeners();
    // darkMode();
});

function eventListeners() {
    const mobileMenu = document.querySelector('.mobile-menu');
    const darkModeBtn = document.querySelector('.dark-mode-btn');
    
    mobileMenu.addEventListener('click', navegacionResponsive);
    darkModeBtn.addEventListener('click', darkMode)
}

function navegacionResponsive() {
    const navegacion = document.querySelector('.navigation');
    navegacion.classList.toggle('mostrar'); 
}

function darkMode() {
    const preferencia = window.matchMedia('(prefers-color-scheme: dark)')
    
    if (preferencia.matches) { 
        document.body.classList.add('dark-mode')
    } else { 
        document.body.classList.remove('dark-mode') 
    }

    preferencia.addEventListener('change', () => {
        if (preferencia.matches) { 
            document.body.classList.add('dark-mode')
        } else { 
            document.body.classList.remove('dark-mode') 
        }
    })

    const darkModeBtn = document.querySelector('.dark-mode-btn');

    darkModeBtn.addEventListener('click', () => 
    document.body.classList.toggle('dark-mode') 
    )
}