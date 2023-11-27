//## Entrance effects ##//

// Funkcja sprawdzająca, czy element jest widoczny w obszarze viewport
function isInViewport(element) {
    var rect = element.getBoundingClientRect();
    var windowHeight = window.innerHeight || document.documentElement.clientHeight;
    var elementTopInView = rect.top >= 0 && rect.top <= windowHeight;
    var elementBottomInView = rect.bottom >= 0 && rect.bottom <= windowHeight;
  
    return elementTopInView || elementBottomInView;
  }
  
  // Funkcja dodająca klasę z opóźnieniem
  function addTransitionClass(element, className, delay) {
    setTimeout(function() {
      element.classList.add(className);
    }, delay);
  }
  
  // Funkcja usuwająca klasę z opóźnieniem
  function removeTransitionClass(element, className, delay) {
    setTimeout(function() {
      element.classList.remove(className);
    }, delay);
  }
  
  // Funkcja obsługująca efekty przejścia dla danego zestawu elementów
  function handleTransition(elements, className, delay) {
    elements.forEach(function(element, index) {
      if (isInViewport(element) && !element.classList.contains(className)) {
        addTransitionClass(element, className, (index + 1) * delay);
      } else if (!isInViewport(element) && element.classList.contains(className)) {
        removeTransitionClass(element, className, delay);
      }
    });
  }

  // Pobranie wszystkich elementów o klasie .fade-up
  var fadeUps = document.querySelectorAll('.fade-up');
  // Dodanie klasy .fade-up--transition do widocznych elementów bez opóźnień
  handleTransition(fadeUps, 'fade-up--transition', 15);
  
  // Pobranie wszystkich elementów o klasie .fade-left
  var fadeLefts = document.querySelectorAll('.fade-left');
  // Dodanie klasy .fade-left--transition do widocznych elementów bez opóźnień
  handleTransition(fadeLefts, 'fade-left--transition', 15);
  
  // Pobranie wszystkich elementów o klasie .fade-right
  var fadeRight = document.querySelectorAll('.fade-right');
  // Dodanie klasy .fade-left--transition do widocznych elementów bez opóźnień
  handleTransition(fadeRight, 'fade-right--transition', 15);
  
  // Pobranie wszystkich elementów o klasie .fade
  var fade = document.querySelectorAll('.fade');
  // Dodanie klasy .fade-transition do widocznych elementów bez opóźnień
  handleTransition(fade, 'fade--transition', 15);
  
  // Nasłuchiwanie na zdarzenie scrolla, aby dodawać/usuwać klasy w odpowiednich momentach
  window.addEventListener('scroll', function() {
    handleTransition(fadeUps, 'fade-up--transition', 15);
    handleTransition(fadeLefts, 'fade-left--transition', 15);
    handleTransition(fadeRight, 'fade-right--transition', 15);
    handleTransition(fade, 'fade--transition', 15);
  });
  
  // Nasłuchiwanie na zdarzenie przewijania strony
window.addEventListener('scroll', function() {
  // Obliczanie wartości zmiennej "--scroll" na podstawie przesunięcia pionowego i wysokości widocznego obszaru okna przeglądarki
  var scrollValue = window.pageYOffset / (document.body.offsetHeight - window.innerHeight);

  // Ustawianie wartości właściwości CSS "--scroll" na obliczoną wartość
  document.body.style.setProperty('--scroll', scrollValue);
}, false);


//## Parralax effects ##//

// Definicja funkcji odpowiedzialnej za efekt paralaksy
function parallax(layer, speed, axis) {
  const items = document.querySelectorAll(layer);
  items.forEach((item) => {
    const rect = item.getBoundingClientRect();
    const windowHeight = window.innerHeight;
    const originalPosition = parseFloat(item.dataset.originalPosition) || 0;

    // Sprawdzenie czy element jest widoczny w obszarze viewport
    if (rect.top < windowHeight && rect.bottom >= 0) {
      const distance = rect.top - windowHeight; // Odległość elementu od górnego brzegu viewport

      if (axis === 'Y') {
        item.style.transform = `translateY(${-distance * speed}px)`; // Przesuń element wzdłuż osi Y
      } else if (axis === 'X') {
        item.style.transform = `translateX(${-distance * speed}px)`; // Przesuń element wzdłuż osi X
      }

      item.dataset.originalPosition = originalPosition;
    } else {
      if (axis === 'Y') {
        item.style.transform = `translateY(${originalPosition}px)`; // Przywróć element do pierwotnej pozycji
      } else if (axis === 'X') {
        item.style.transform = `translateX(${originalPosition}px)`; // Przywróć element do pierwotnej pozycji
      }

      item.dataset.originalPosition = originalPosition;
    }
  });
}

// Dodanie nasłuchiwania na zdarzenie przewijania dokumentu
document.addEventListener('scroll', function () {
  if (window.innerWidth >= 1280) {
    // Wywołanie funkcji parallax dla wybranych elementów z różnymi prędkościami animacji wzdłuż osi Y (pionowej)
    parallax('.translateY01', 0.1, 'Y');
    parallax('.translateY-01', -0.1, 'Y');
    parallax('.translateY015', 0.15, 'Y');
    parallax('.translateY-015', -0.15, 'Y');
    parallax('.translateY02', 0.2, 'Y');
    parallax('.translateY-02', -0.2, 'Y');
    parallax('.translateY025', 0.25, 'Y');
    parallax('.translateY-025', -0.25, 'Y');

    // Wywołanie funkcji parallax dla wybranych elementów z różnymi prędkościami animacji wzdłuż osi X (poziomej)
    parallax('.translateX01', 0.1, 'X');
    parallax('.translateX-01', -0.1, 'X');
    parallax('.translateX015', 0.15, 'X');
    parallax('.translateX-015', -0.15, 'X');
    parallax('.translateX02', 0.2, 'X');
    parallax('.translateX-02', -0.2, 'X');
    parallax('.translateX025', 0.25, 'X');
    parallax('.translateX-025', -0.25, 'X');
  }
});