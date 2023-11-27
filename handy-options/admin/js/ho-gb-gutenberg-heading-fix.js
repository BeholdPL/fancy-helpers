function findAndModify() {
  var rootContainers = document.querySelectorAll('.gb-is-root-block');

  if (rootContainers.length === 0) {
    console.warn('No elements with the class .gb-is-root-block found. Checking again in a moment...');
  } else {
    clearInterval(intervalId); // Stop the interval
    rootContainers.forEach(function(container) {
      var spanElement = document.createElement('span');
      // You can add text or other attributes to the span element if needed
      // spanElement.textContent = 'Text for the span';

      // Add the span as the first child to each container
      container.insertBefore(spanElement, container.firstChild);
    });
    console.log('Found elements with the class .gb-is-root-block and added <span> elements.');
  }
}

var intervalId = setInterval(findAndModify, 1000); // Check every second (1000 ms)
