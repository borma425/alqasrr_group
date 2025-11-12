// Location Timeline - Align line with dots and car
document.addEventListener('DOMContentLoaded', function() {
  const timeline = document.querySelector('.location-timeline');
  if (!timeline) return;

  function alignTimeline() {
    const dots = timeline.querySelectorAll('.timeline-dot');
    if (dots.length === 0) return;

    // Calculate the average top position of all dots
    let totalTop = 0;
    dots.forEach(dot => {
      const rect = dot.getBoundingClientRect();
      const timelineRect = timeline.getBoundingClientRect();
      const relativeTop = rect.top - timelineRect.top + (rect.height / 2);
      totalTop += relativeTop;
    });

    const averageTop = totalTop / dots.length;
    const percentage = (averageTop / timeline.offsetHeight) * 100;

    // Set the line position to match the dots
    timeline.style.setProperty('--line-position', `${percentage}%`);

    // Also align the car image
    const car = document.querySelector('.location-map');
    if (car) {
      car.style.top = `${percentage}%`;
    }
  }

  // Align on load
  alignTimeline();

  // Align on resize
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(alignTimeline, 100);
  });
});

