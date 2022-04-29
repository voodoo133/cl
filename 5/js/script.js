let participants = [];

const form = document.querySelector('.form');
form.addEventListener('submit', function(e) {
  e.preventDefault();

  const inputField = form.querySelector('.form__input');
  const input = inputField.value.trim();

  if (input.length === 0) {
    showError('Необходимо ввести данные');

    return;
  }

  const names = input.split(',');

  let addedParticipants = [];
  let lastIndex = participants.reduce((max, c) => (max < c.id) ? c.id : max, 0);

  for (let i = 0; i < names.length; i++) {
    const name = names[i].trim();

    if (!/^[а-яА-Я]+$/.test(name)) {
      showError('Должны быть только кириллические символы в именах');
      return;
    }

    addedParticipants.push({
      id: ++lastIndex,
      name: name,
      score: Math.round(Math.random() * 100)
    });
  }

  participants = participants.concat(addedParticipants);

  const table = document.querySelector('.table');
  const tBody = table.querySelector("tbody");
  addedParticipants.forEach((p) => tBody.append(addTableRow(p)));

  if (table.classList.contains('table--hidden'))
    table.classList.remove('table--hidden');

  inputField.value = '';
});

const tableHeaders = document.querySelectorAll('.table th');
tableHeaders.forEach(function(th) {
  th.addEventListener('click', function() {
    const field = this.dataset.col;
    let sortType = this.dataset.sortType;

    if (sortType === 'asc') sortType = 'desc';
    else sortType = 'asc';

    participants.sort(function(a, b) {
      if (sortType === 'asc')
        return a[field] > b[field] ? 1 : -1;
      else 
        return a[field] > b[field] ? -1 : 1;
    });

    const tBody = document.querySelector(".table tbody");
    tBody.innerHTML = '';
    participants.forEach((p) => tBody.append(addTableRow(p)));

    tableHeaders.forEach((th) => th.removeAttribute('data-sort-type'));
    this.setAttribute('data-sort-type', sortType);
  });
});

const modal = document.getElementById('alert-modal');
modal.addEventListener('close', function() {
  modal.querySelector('.modal-header').textContent = '';

  const msg = modal.querySelector('.msg');
  msg.textContent = '';
  msg.classList.remove('msg--error');
});

function showError(errorMsg) {
  const modal = document.getElementById('alert-modal');

  modal.querySelector('.modal-header').textContent = 'Ошибка';

  const msg = modal.querySelector('.msg');
  msg.textContent = errorMsg;
  msg.classList.add('msg--error');

  modal.classList.add('open');
}

function addTableRow(participant) {
  const row = document.createElement('tr');

  row.innerHTML = `<td data-label="ID">${participant.id}</td>
                   <td data-label="Имя">${participant.name}</td>
                   <td data-label="Очки">${participant.score}</td>`;

  return row;
}