// Tauler Kanban: arrossegar tasques, veure el detall i crear tasques noves

const columnes = document.querySelectorAll('.columna');
let tascaArrossegada = null;

const nomsTipus = {
    disseny: 'Disseny',
    frontend: 'Front-end',
    backend: 'Back-end',
    bd: 'Base de dades',
    qa: 'QA'
};

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

// Crear una tasca nova a la columna «Per fer»
const formulari = document.querySelector('#formNovaTasca');

formulari.addEventListener('submit', function (event) {
    event.preventDefault();

    const clauTipus = document.querySelector('#novaTipus').value;
    const nomTipus = nomsTipus[clauTipus];
    const titol = document.querySelector('#novaTitol').value;
    const responsable = document.querySelector('#novaResponsable').value;

    // La data del formulari ve com 2026-11-20 i la volem com 20/11/2026
    const parts = document.querySelector('#novaData').value.split('-');
    const data = parts[2] + '/' + parts[1] + '/' + parts[0];

    const tasca = document.createElement('article');
    tasca.classList.add('tasca');
    tasca.classList.add('vora-' + clauTipus);
    tasca.setAttribute('draggable', 'true');
    tasca.setAttribute('data-bs-toggle', 'modal');
    tasca.setAttribute('data-bs-target', '#modalTasca');
    tasca.dataset.estat = 'per-fer';
    tasca.dataset.titol = titol;
    tasca.dataset.tipus = nomTipus;
    tasca.dataset.responsable = responsable;
    tasca.dataset.data = data;
    tasca.dataset.descripcio = document.querySelector('#novaDescripcio').value;

    const etiqueta = document.createElement('span');
    etiqueta.classList.add('tipus');
    etiqueta.classList.add('tipus-' + clauTipus);
    etiqueta.textContent = nomTipus;

    const textTitol = document.createElement('p');
    textTitol.classList.add('tasca-titol');
    textTitol.textContent = titol;

    const textPeu = document.createElement('small');
    textPeu.classList.add('text-secondary');
    textPeu.textContent = data + ' · ' + responsable;

    tasca.appendChild(etiqueta);
    tasca.appendChild(textTitol);
    tasca.appendChild(textPeu);

    document.querySelector('.columna[data-estat="per-fer"] .columna-tasques').appendChild(tasca);
    prepararTasca(tasca);
    actualitzarComptadors();

    document.querySelector('#novaTitol').value = '';
    document.querySelector('#novaDescripcio').value = '';
    document.querySelector('#novaData').value = '';
    document.querySelector('#modalNovaTasca .btn-close').click();
});

actualitzarComptadors();
