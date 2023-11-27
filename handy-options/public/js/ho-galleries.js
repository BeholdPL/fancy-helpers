// Znajdź wszystkie kontenery o klasie ".addLightbox"
const containers = document.querySelectorAll('.addLightbox, .ho-lightbox');

// Iteruj przez każdy kontener
containers.forEach((container) => {
  // Wygeneruj losową wartość 4-cyfrową
  const randomValue = Math.floor(Math.random() * 10000);

  // Znajdź wszystkie elementy <a> wewnątrz kontenera
  const links = container.querySelectorAll('a');

  // Iteruj przez każdy element <a> i dodaj atrybut "data-fslightbox" z losową wartością
  links.forEach((link) => {
    link.setAttribute('data-fslightbox', randomValue);
  });
});

// Pobierz wszystkie elementy <img> o klasie .singleLightbox
const images = document.querySelectorAll("img.singleLightbox, img.ho-single-lightbox");

// Przejdź przez każdy element <img> i dodaj atrybut 'data-fslightbox' do pierwszego nadrzędnego elementu <a>
images.forEach(image => {
  const parentAnchor = image.closest("a"); // Znajdź pierwszy nadrzędny element <a>
  if (parentAnchor) {
    const randomValue = Math.floor(Math.random() * 10000); // Generowanie losowej wartości z zakresu 0-9999
    parentAnchor.setAttribute("data-fslightbox", randomValue);
  }
});

// Pobierz wszystkie elementy <img> o klasie .singleLightbox
const imagesx = document.querySelectorAll("figure.singleLightbox, figure.ho-single-lightbox");

// Iteruj przez każdy kontener
imagesx.forEach((image) => {
  // Wygeneruj losową wartość 4-cyfrową
  const randomValue = Math.floor(Math.random() * 10000);

  // Znajdź wszystkie elementy <a> wewnątrz kontenera
  const links = image.querySelectorAll('a');

  // Iteruj przez każdy element <a> i dodaj atrybut "data-fslightbox" z losową wartością
  links.forEach((link) => {
    link.setAttribute('data-fslightbox', randomValue);
  });
});