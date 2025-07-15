// Function to generate a random 4-digit value
function generateRandomValue() {
  return Math.floor(Math.random() * 10000);
}

// Function to add 'data-fslightbox' attribute to <a> elements
function addDataFslightboxAttribute(elements) {
  const randomValue = generateRandomValue();
  elements.forEach((element) => {
    element.setAttribute('data-fslightbox', randomValue);
  });
}

// Find all containers with class ".addLightbox" or ".bhld-lightbox" or ".fh-lightbox"
const containers = document.querySelectorAll('.addLightbox, .bhld-lightbox, .fh-lightbox');
containers.forEach((container) => {
  // Find all <a> elements inside the container and add "data-fslightbox" attribute
  addDataFslightboxAttribute(container.querySelectorAll('a'));
});

// Find all <img> elements with class ".singleLightbox" or ".bhld-single-lightbox" or ".fh-lightbox"
const images = document.querySelectorAll("img.singleLightbox, img.bhld-single-lightbox, img.fh-lightbox");
images.forEach((image) => {
  // Find the nearest parent <a> element and add "data-fslightbox" attribute
  const parentAnchor = image.closest("a");
  if (parentAnchor) {
    addDataFslightboxAttribute([parentAnchor]);
  }
});

// Find all <img> elements with class ".singleLightbox" inside <figure> containers
const imagesInFigures = document.querySelectorAll("figure.singleLightbox img, figure.bhld-single-lightbox img, figure.fh-lightbox img");
imagesInFigures.forEach((image) => {
  // Find all <a> elements inside the figure container and add "data-fslightbox" attribute
  addDataFslightboxAttribute(image.closest('figure').querySelectorAll('a'));
});
