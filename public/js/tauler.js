// Tauler Kanban: arrossegar tasques, veure el detall i crear tasques noves

const columnes = document.querySelectorAll('.columna');
const nomsEstats = {
    'per-fer': 'Per fer',
    'en-progres': 'En progrés',
    'feta': 'Feta'
};

let tascaArrossegada = null;
let comptadorIds = 100;

function actualitzarComptadors() {
    columnes.forEach(function (columna) {
        const total = columna.querySelectorAll('.tasca').length;
        columna.querySelector('.comptador').textContent = total;
    });
}

function bloquejarTasca(tasca) {
    tasca.setAttribute('draggable', 'false');
    tasca.classList.add('bloquejada');
}

function mostrarDetall(tasca) {
    document.querySelector('#detallTitol').textContent = tasca.dataset.titol;
    document.querySelector('#detallDescripcio').textContent = tasca.dataset.descripcio || 'Sense descripció.';
    document.querySelector('#detallEstat').textContent = nomsEstats[tasca.dataset.estat];
    document.querySelector('#detallResponsable').textContent = tasca.dataset.responsable;

    const parts = tasca.dataset.data.split('-');
    document.querySelector('#detallData').textContent = parts[2] + '/' + parts[1] + '/' + parts[0];

    const etiqueta = document.querySelector('#detallTipus');
    etiqueta.textContent = tasca.dataset.tipus;
    etiqueta.className = 'tipus mb-2 tipus-' + tasca.dataset.clauTipus;

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
        tasca.classList.add('arrossegant');
    });

    tasca.addEventListener('dragend', function () {
        tasca.classList.remove('arrossegant');
        tascaArrossegada = null;
    });

    tasca.addEventListener('click', function () {
        mostrarDetall(tasca);
    });
}

document.querySelectorAll('.tasca').forEach(function (tasca) {
    prepararTasca(tasca);
});

columnes.forEach(function (columna) {
    columna.addEventListener('dragover', function (event) {
        event.preventDefault();
        columna.classList.add('sobre');
    });

    columna.addEventListener('dragleave', function () {
        columna.classList.remove('sobre');
    });

    columna.addEventListener('drop', function (event) {
        event.preventDefault();
        columna.classList.remove('sobre');

        if (tascaArrossegada == null) {
            return;
        }

        // Una tasca feta no es pot tornar a moure
        if (tascaArrossegada.dataset.estat == 'feta') {
            return;
        }

        const nouEstat = columna.dataset.estat;
        columna.querySelector('.columna-tasques').appendChild(tascaArrossegada);
        tascaArrossegada.dataset.estat = nouEstat;

        if (nouEstat == 'feta') {
            bloquejarTasca(tascaArrossegada);
        }

        // Quan hi hagi API, aquí s'enviarà el nou estat al servidor
        actualitzarComptadors();
    });
});

// Crear una tasca nova a la columna «Per fer»
const formulari = document.querySelector('#formNovaTasca');

formulari.addEventListener('submit', function (event) {
    event.preventDefault();

    const titol = document.querySelector('#novaTitol').value;
    const descripcio = document.querySelector('#novaDescripcio').value;
    const selectTipus = document.querySelector('#novaTipus');
    const clauTipus = selectTipus.value;
    const nomTipus = selectTipus.options[selectTipus.selectedIndex].text;
    const data = document.querySelector('#novaData').value;
    const responsable = document.querySelector('#novaResponsable').value;
    const parts = data.split('-');

    comptadorIds++;

    const tasca = document.createElement('article');
    tasca.className = 'tasca tipus-' + clauTipus;
    tasca.setAttribute('draggable', 'true');
    tasca.setAttribute('data-bs-toggle', 'modal');
    tasca.setAttribute('data-bs-target', '#modalTasca');
    tasca.dataset.id = comptadorIds;
    tasca.dataset.titol = titol;
    tasca.dataset.tipus = nomTipus;
    tasca.dataset.clauTipus = clauTipus;
    tasca.dataset.estat = 'per-fer';
    tasca.dataset.responsable = responsable;
    tasca.dataset.data = data;
    tasca.dataset.descripcio = descripcio;

    const etiqueta = document.createElement('span');
    etiqueta.className = 'tipus mb-2 tipus-' + clauTipus;
    etiqueta.textContent = nomTipus;

    const textTitol = document.createElement('p');
    textTitol.className = 'tasca-titol text-white';
    textTitol.textContent = titol;

    const peu = document.createElement('div');
    peu.className = 'd-flex justify-content-between align-items-center';

    const textData = document.createElement('small');
    textData.className = 'text-secondary';
    textData.textContent = parts[2] + '/' + parts[1];

    const avatar = document.createElement('span');
    avatar.className = 'avatar';
    avatar.title = responsable;
    avatar.textContent = responsable.charAt(0);

    peu.appendChild(textData);
    peu.appendChild(avatar);
    tasca.appendChild(etiqueta);
    tasca.appendChild(textTitol);
    tasca.appendChild(peu);

    document.querySelector('.columna[data-estat="per-fer"] .columna-tasques').appendChild(tasca);
    prepararTasca(tasca);
    actualitzarComptadors();

    // Quan hi hagi API, aquí s'enviarà la tasca nova al servidor
    formulari.reset();
    document.querySelector('#modalNovaTasca [data-bs-dismiss="modal"]').click();
});

actualitzarComptadors();
