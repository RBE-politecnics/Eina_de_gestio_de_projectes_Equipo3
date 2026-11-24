// Tauler Kanban: les tasques es demanen i es desen a l'API amb fetch

const tauler = document.querySelector('#tauler');
const api = tauler.dataset.api;
const projecteId = tauler.dataset.projecte;
const usuariId = tauler.dataset.usuari;
const esAdmin = tauler.dataset.admin == '1';
const token = document.querySelector('meta[name="csrf-token"]').content;

const columnes = document.querySelectorAll('.columna');
let tascaArrossegada = null; // la targeta que s'està arrossegant
let tascaOberta = null; // les dades de la tasca que es veu al modal
const tasques = {}; // les dades de cada tasca, amb el seu id com a clau

// Crida a l'API i torna una promesa amb { ok, dades }
function cridarApi(adreca, metode, dades) {
    const opcions = {
        method: metode,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        }
    };

    if (dades) {
        opcions.body = JSON.stringify(dades);
    }

    return fetch(api + adreca, opcions).then(function (resposta) {
        if (resposta.status == 204) {
            return { ok: true, dades: null };
        }

        return resposta.json().then(function (dades) {
            return { ok: resposta.ok, dades: dades };
        });
    });
}

function mostrarMissatge(text, tipus) {
    const caixa = document.querySelector('#missatgeTauler');
    caixa.textContent = text;
    caixa.classList.remove('d-none');
    caixa.classList.remove('alert-success');
    caixa.classList.remove('alert-danger');
    caixa.classList.add('alert-' + tipus);
}

// Si hi ha errors de validació agafem el primer; si no, el missatge general
function textError(dades) {
    if (dades.errors) {
        const primer = Object.keys(dades.errors)[0];
        return dades.errors[primer][0];
    }

    return dades.message || 'Hi ha hagut un error.';
}

function actualitzarComptadors() {
    columnes.forEach(function (columna) {
        columna.querySelector('.comptador').textContent = columna.querySelectorAll('.tasca').length;
    });

    document.querySelector('#totalTasques').textContent = document.querySelectorAll('.tasca').length;
}

// Crea la targeta d'una tasca i la posa a la seva columna
function pintarTasca(tasca) {
    tasques[tasca.id] = tasca;

    const targeta = document.createElement('article');
    targeta.classList.add('tasca');
    targeta.style.borderLeftColor = tasca.tipus_color;
    targeta.setAttribute('draggable', tasca.pot && !tasca.bloquejada ? 'true' : 'false');
    targeta.setAttribute('data-bs-toggle', 'modal');
    targeta.setAttribute('data-bs-target', '#modalTasca');
    targeta.dataset.id = tasca.id;

    if (tasca.bloquejada) {
        targeta.classList.add('bloquejada');
    }

    const etiqueta = document.createElement('span');
    etiqueta.classList.add('tipus');
    etiqueta.style.backgroundColor = tasca.tipus_color;
    etiqueta.textContent = tasca.tipus_nom;

    const titol = document.createElement('p');
    titol.classList.add('tasca-titol');
    titol.textContent = tasca.nom;

    const peu = document.createElement('small');
    peu.classList.add('text-secondary');
    peu.textContent = tasca.data_text + ' · ' + tasca.responsable;

    targeta.appendChild(etiqueta);
    targeta.appendChild(titol);
    targeta.appendChild(peu);

    prepararTasca(targeta);

    document.querySelector('.columna[data-estat-id="' + tasca.estats_id + '"] .columna-tasques').appendChild(targeta);
    actualitzarComptadors();
}

// Quan una tasca canvia, treiem la targeta vella i la tornem a pintar
function substituirTasca(tasca) {
    treureTasca(tasca.id);
    pintarTasca(tasca);
}

function treureTasca(id) {
    const vella = document.querySelector('.tasca[data-id="' + id + '"]');

    if (vella) {
        vella.remove();
    }

    delete tasques[id];
    actualitzarComptadors();
}

function tancarModal() {
    document.querySelector('#modalTasca .btn-close').click();
}

function mostrarDetall(tasca) {
    tascaOberta = tasca;

    document.querySelector('#detallTitol').textContent = tasca.nom;
    document.querySelector('#detallDescripcio').textContent = tasca.comentaris;
    document.querySelector('#detallTipus').textContent = tasca.tipus_nom;
    document.querySelector('#detallEstat').textContent = tasca.estat_nom;
    document.querySelector('#detallResponsable').textContent = tasca.responsable;
    document.querySelector('#detallData').textContent = tasca.data_text;

    const avis = document.querySelector('#detallBloqueig');
    const zonaAssignar = document.querySelector('#zonaAssignar');
    const zonaEditar = document.querySelector('#zonaEditar');

    avis.classList.add('d-none');
    zonaAssignar.classList.add('d-none');
    zonaEditar.classList.add('d-none');

    if (tasca.bloquejada) {
        avis.classList.remove('d-none');
    } else if (!tasca.pot) {
        zonaAssignar.classList.remove('d-none');
    } else {
        // Omplim el formulari d'edició amb les dades de la tasca
        document.querySelector('#editTitol').value = tasca.nom;
        document.querySelector('#editDescripcio').value = tasca.comentaris;
        document.querySelector('#editTipus').value = tasca.tipus_id || '';
        document.querySelector('#editEstat').value = tasca.estats_id;
        document.querySelector('#editData').value = tasca.data;

        if (esAdmin) {
            document.querySelector('#editResponsable').value = tasca.users_id;
        }

        zonaEditar.classList.remove('d-none');
    }
}

function prepararTasca(targeta) {
    targeta.addEventListener('dragstart', function () {
        tascaArrossegada = targeta;
    });

    targeta.addEventListener('click', function () {
        mostrarDetall(tasques[targeta.dataset.id]);
    });
}

// Arrossegar: cada columna accepta targetes
columnes.forEach(function (columna) {
    // Sense preventDefault el navegador no deixa deixar anar res a sobre
    columna.addEventListener('dragover', function (event) {
        event.preventDefault();
    });

    columna.addEventListener('drop', function (event) {
        event.preventDefault();

        if (tascaArrossegada == null) {
            return;
        }

        const tasca = tasques[tascaArrossegada.dataset.id];
        tascaArrossegada = null;

        // Una tasca bloquejada (feta) no es pot tornar a moure, ni deixar-la a la mateixa columna
        if (tasca.bloquejada || tasca.estats_id == columna.dataset.estatId) {
            return;
        }

        cridarApi('/tasques/' + tasca.id + '/estat', 'PATCH', { estats_id: columna.dataset.estatId }).then(function (resposta) {
            if (resposta.ok) {
                substituirTasca(resposta.dades.data);
            } else {
                mostrarMissatge(textError(resposta.dades), 'danger');
            }
        });
    });
});

// Editar una tasca
document.querySelector('#formEditarTasca').addEventListener('submit', function (event) {
    event.preventDefault();

    const dades = {
        nom: document.querySelector('#editTitol').value,
        comentaris: document.querySelector('#editDescripcio').value,
        tipus_id: document.querySelector('#editTipus').value || null,
        estats_id: document.querySelector('#editEstat').value,
        data_finalitzacio: document.querySelector('#editData').value || null
    };

    if (esAdmin) {
        dades.users_id = document.querySelector('#editResponsable').value;
    }

    cridarApi('/tasques/' + tascaOberta.id, 'PUT', dades).then(function (resposta) {
        if (resposta.ok) {
            substituirTasca(resposta.dades.data);
            tancarModal();
            mostrarMissatge('Tasca actualitzada.', 'success');
        } else {
            mostrarMissatge(textError(resposta.dades), 'danger');
            tancarModal();
        }
    });
});

// Eliminar una tasca
document.querySelector('#botoEliminar').addEventListener('click', function () {
    if (!confirm('Segur que vols eliminar aquesta tasca?')) {
        return;
    }

    cridarApi('/tasques/' + tascaOberta.id, 'DELETE').then(function (resposta) {
        if (resposta.ok) {
            treureTasca(tascaOberta.id);
            mostrarMissatge('Tasca eliminada.', 'success');
        } else {
            mostrarMissatge(textError(resposta.dades), 'danger');
        }

        tancarModal();
    });
});

// Un membre pot assignar-se qualsevol tasca
document.querySelector('#botoAssignar').addEventListener('click', function () {
    cridarApi('/tasques/' + tascaOberta.id + '/assignar-me', 'PATCH').then(function (resposta) {
        if (resposta.ok) {
            substituirTasca(resposta.dades.data);
            mostrarMissatge('Ara aquesta tasca és teva.', 'success');
        } else {
            mostrarMissatge(textError(resposta.dades), 'danger');
        }

        tancarModal();
    });
});

// Crear una tasca nova
document.querySelector('#formNovaTasca').addEventListener('submit', function (event) {
    event.preventDefault();

    const dades = {
        projecte_id: projecteId,
        nom: document.querySelector('#novaTitol').value,
        comentaris: document.querySelector('#novaDescripcio').value,
        tipus_id: document.querySelector('#novaTipus').value || null,
        estats_id: document.querySelector('#novaEstat').value,
        data_finalitzacio: document.querySelector('#novaData').value || null
    };

    if (esAdmin) {
        dades.users_id = document.querySelector('#novaResponsable').value;
    }

    cridarApi('/tasques', 'POST', dades).then(function (resposta) {
        if (resposta.ok) {
            pintarTasca(resposta.dades.data);
            document.querySelector('#novaTitol').value = '';
            document.querySelector('#novaDescripcio').value = '';
            document.querySelector('#novaData').value = '';
            document.querySelector('#modalNovaTasca .btn-close').click();
            mostrarMissatge('Tasca creada.', 'success');
        } else {
            mostrarMissatge(textError(resposta.dades), 'danger');
            document.querySelector('#modalNovaTasca .btn-close').click();
        }
    });
});

// En entrar, demanem a l'API les tasques del projecte
cridarApi('/projectes/' + projecteId + '/tasques', 'GET').then(function (resposta) {
    if (resposta.ok) {
        resposta.dades.data.forEach(function (tasca) {
            pintarTasca(tasca);
        });
    } else {
        mostrarMissatge(textError(resposta.dades), 'danger');
    }
});
