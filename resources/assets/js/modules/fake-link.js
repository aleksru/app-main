document.querySelectorAll('.fake-link')
    .forEach(elem => elem.addEventListener('click', window.open(this.dataset.url, '_blank')));