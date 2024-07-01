document.getElementById('toggle-filters').addEventListener('click', function() {
    var filters = document.getElementById('filters');
    var arrow = document.getElementById('arrow');
    
    if (filters.style.display === 'none' || filters.style.display === '') {
        filters.style.display = 'block';
        arrow.innerHTML = '&#9660;';
    } else {
        filters.style.display = 'none';
        arrow.innerHTML = '&#9654;';
    }
});