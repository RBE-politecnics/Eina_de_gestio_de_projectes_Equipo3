// Tauler Kanban: arrossegar tasques, veure el detall i crear tasques noves

const columnes = document.querySelectorAll('.columna');
let tascaArrossegada = null;

function actualitzarComptadors() {
    columnes.forEach(function (columna) {
        columna.querySelector('.comptador').textContent = columna.querySelectorAll('.tasca').length;
    });
}

function mostrarDetall(tasca) {
    document.querySelector('#detallTitol').textContent = tasca.dataset.titol;
    document.querySelector('#detallDescripcio').textContent = tasca.dataset.descripcio;
    document.querySelector('#detallTipus').textContent = tasca.dataset.tipus;
    document.querySelector('#detallResponsable').textContent = tasca.dataset.responsable;
    document.querySelector('#detallData').textContent = tasca.dataset.data;

    // El nom de l'estat el té la columna amb el mateix data-estat que la tasca
    const columna = document.querySelector('.columna[data-estat="' + tasca.dataset.estat + '"]');
    document.querySelector('#detallEstat').textContent = columna.dataset.nom;

    const avis = document.querySelector('#detallBloqueig');
    if (tasca.dataset.estat == 'feta') {
        avis.classList.remove('d-none');
    } else {
        avis.classList.add('d-none');
    }
}

function prepararTasca(tasca) {
    tasca.addEventListener('dragstart', function () {
        tascaArrossegada = tasca;
    });

    tasca.addEventListener('click', function () {
        mostrarDetall(tasca);
    });
}

document.querySelectorAll('.tasca').forEach(function (tasca) {
    prepararTasca(tasca);
});

columnes.forEach(function (columna) {
    // Sense preventDefault el navegador no deixa deixar anar res a sobre
    columna.addEventListener('dragover', function (event) {
        event.preventDefault();
    });

    columna.addEventListener('drop', function (event) {
        event.preventDefault();

        // Una tasca feta no es pot tornar a moure
        if (tascaArrossegada == null || tascaArrossegada.dataset.estat == 'feta') {
            return;
        }

        columna.querySelector('.columna-tasques').appendChild(tascaArrossegada);
        tascaArrossegada.dataset.estat = columna.dataset.estat;

        if (columna.dataset.estat == 'feta') {
            tascaArrossegada.setAttribute('draggable', 'false');
            tascaArrossegada.classList.add('bloquejada');
        }

        tascaArrossegada = null;
        actualitzarComptadors();
    });
});

actualitzarComptadors();
