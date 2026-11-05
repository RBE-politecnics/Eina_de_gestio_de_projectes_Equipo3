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

    // El nom de l'estat el té la columna on està la tasca
    document.querySelector('#detallEstat').textContent = tasca.closest('.columna').dataset.nom;

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

document.querySelectorAll('.tasca').forEach(prepararTasca);

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

    const selectTipus = document.querySelector('#novaTipus');
    const clauTipus = selectTipus.value;
    const nomTipus = selectTipus.options[selectTipus.selectedIndex].text;
    const titol = document.querySelector('#novaTitol').value;
    const responsable = document.querySelector('#novaResponsable').value;

    // La data del formulari ve com 2026-11-20 i la volem com 20/11/2026
    const parts = document.querySelector('#novaData').value.split('-');
    const data = parts[2] + '/' + parts[1] + '/' + parts[0];

    const tasca = document.createElement('article');
    tasca.className = 'tasca vora-' + clauTipus;
    tasca.setAttribute('draggable', 'true');
    tasca.setAttribute('data-bs-toggle', 'modal');
    tasca.setAttribute('data-bs-target', '#modalTasca');
    tasca.dataset.estat = 'per-fer';
    tasca.dataset.titol = titol;
    tasca.dataset.tipus = nomTipus;
    tasca.dataset.responsable = responsable;
    tasca.dataset.data = data;
    tasca.dataset.descripcio = document.querySelector('#novaDescripcio').value;

    tasca.innerHTML = '<span class="tipus tipus-' + clauTipus + '"></span>'
        + '<p class="tasca-titol"></p>'
        + '<small class="text-secondary"></small>';
    tasca.querySelector('.tipus').textContent = nomTipus;
    tasca.querySelector('.tasca-titol').textContent = titol;
    tasca.querySelector('small').textContent = data + ' · ' + responsable;

    document.querySelector('.columna[data-estat="per-fer"] .columna-tasques').appendChild(tasca);
    prepararTasca(tasca);
    actualitzarComptadors();

    formulari.reset();
    document.querySelector('#modalNovaTasca .btn-close').click();
});

actualitzarComptadors();
