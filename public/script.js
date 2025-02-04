const cube = document.querySelector('.cube');
const links = document.querySelectorAll('.sidebar a');

const rotations = {
    home: 'rotateY(0deg)',
    services: 'rotateY(90deg)',
    about: 'rotateY(-90deg)',
    contact: 'rotateY(180deg)'
};

links.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const target = e.target.getAttribute('data-target');
        cube.style.transform = rotations[target];
        history.pushState(null, '', e.target.href);
    });
});
