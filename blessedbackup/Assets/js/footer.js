document.addEventListener('DOMContentLoaded', async function () {
  const components = [
    { id: 'navbar-container', path: './Assets/navbar.html' },
    { id: 'footer-container', path: './Assets/footer.html' },
  ];

  for (const component of components) {
    const container = document.getElementById(component.id);
    if (!container) continue;

    try {
      const response = await fetch(component.path);
      if (!response.ok) throw new Error(`${component.path} not found`);
      container.innerHTML = await response.text();
    } catch (error) {
      console.error('Unable to load shared component:', error);
    }
  }
});

