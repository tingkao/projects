
let getGame = '';
let pressTimes = 0;

function createReq(url, dataHandler) {
  const req = new XMLHttpRequest();
  req.onload = () => {
    let result;
    if (req.status >= 200 && req.status < 400) {
      try {
        result = JSON.parse(req.responseText);
        dataHandler(null, result);
      } catch (err) {
        // normally handled by backend
        console.log('System Error', err);
        dataHandler(err);
      }
    } else {
      console.log('Error');
    }
  };
  req.open('GET', url, true);
  req.setRequestHeader('Client-ID', 'uwpvt9ahl8ysuv4r7ditx45msdv1sb');
  req.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
  req.send();
}

function topGameList(err, result) {
  let gameList = 5;
  if (window.screen.width <= 767) {
    gameList = 6;
  }
  for (let i = 0; i < gameList; i += 1) {
    const topList = document.createElement('li');
    topList.innerText = result.top[i].game.name;
    document.querySelector('.navbar__aside').appendChild(topList);
  }
  document.querySelector('li').click();
}

function createCard(resultItem) {
  /* Destruct Js
  const user = resultItem.channel.name;
  let description = resultItem.channel.description;
  const avatar = resultItem.channel.logo;
  const preview = resultItem.preview.medium;
  const video = resultItem.channel.url; */
  const { channel: { name: user } } = resultItem;
  let { channel: { description } } = resultItem;
  const { channel: { logo: avatar } } = resultItem;
  const { preview: { medium: preview } } = resultItem;
  const { channel: { url: video } } = resultItem;
  const card = document.createElement('div');

  if (!(description)) {
    description = getGame;
  }

  card.classList.add('card');
  card.innerHTML = `
    <div class="card_img"><img src="${preview}" alt=""></div>
    <div class="avatar"><img src="${avatar}" alt=""></div>
    <div class="card_info">
        <div class="game">${description}</div>
        <div class="user">${user}</div>
    </div>
    <a href="${video}" target="_blank"></a>`;
  document.querySelector('.cards_group').appendChild(card);
}

//fake card for formatting cards in the page by flex
function createFakeCard() {
  const card = document.createElement('div');
  card.classList.add('card');
  card.classList.add('invisible');
  card.innerHTML = `
    <div class="card_img"><img src="" alt=""></div>
    <div class="avatar"><img src="" alt=""></div>
    <div class="card_info">
        <div class="game"></div>
        <div class="user"></div>
    </div>`;
  document.querySelector('.cards_group').appendChild(card);
}

function repeat(n, method) {
  for (let i = 0; i < n; i += 1) {
    method();
  }
}

const urlTop = 'https://api.twitch.tv/kraken/games/top/?limit=6';
createReq(urlTop, topGameList);

document.querySelector('.navbar__aside').addEventListener('click', (e) => {
  let gameData;
  if (e.target.nodeName === 'LI') {
    pressTimes = 0;
    document.querySelector('.cards_group').innerHTML = '';
    getGame = e.target.innerText;
    document.querySelector('.gameName').innerText = getGame;
    if (e.target.parentNode.querySelector('li.active')) {
      e.target.parentNode.querySelector('li.active').classList.remove('active');
    }
    e.target.classList.add('active');

    gameData = (err, result) => {
      for (let j = 0; j < result.streams.length; j += 1) {
        const resultItem = result.streams[j];
        createCard(resultItem, result, getGame);
      }
      repeat(3, createFakeCard);
    };

    const urlData = `https://api.twitch.tv/kraken/streams/?game=${getGame}&limit=20`;
    createReq(urlData, gameData);
  }
});

document.querySelector('.more_btn').addEventListener('click', () => {
  pressTimes += 1;
  document.querySelectorAll('.card.invisible').forEach((e) => {
    e.style.position = 'absolute';
  });
  let moreData;
  if (pressTimes <= 4) {
    const loadingNum = pressTimes * 20;

    moreData = (err, result) => {
      if (loadingNum >= result.streams.length) {
        alert('End of Stream');
      }
      for (let k = loadingNum; k < result.streams.length; k += 1) {
        const resultItem = result.streams[k];
        /* createCard(resultItem, result, getGame);
        -> ESLint: 'getGame' is already declared in the upper scope */
        createCard(resultItem);
      }
      repeat(3, createFakeCard);
    };
    const urlData = `https://api.twitch.tv/kraken/streams/?game=${getGame}&limit=${(loadingNum + 20)}`;
    createReq(urlData, moreData);
  } else {
    alert('End of Stream');
  }
});
