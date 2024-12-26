/**
 * This script handles fetching and displaying coach data from the backend API.
 * It allows filtering by search terms and sorting by rate.
 */

document.addEventListener('DOMContentLoaded', () => {
    const coachList = document.getElementById('coach-list');
    const searchInput = document.getElementById('search');
    const sortSelect = document.getElementById('sort');

    /**
     * Fetches coach data from the backend API.
     *
     * @param {string} search - The search term to filter coaches by name or location.
     * @param {string} sort - The sorting order for the coaches (rate-asc or rate-desc).
     * @returns {Promise<Array>} - A promise that resolves to an array of coach objects.
     */
    async function fetchCoaches(search = '', sort = '') {
        const url = new URL('http://localhost:8080/coaches.php');
        url.searchParams.append('sort', sort);

        if (search) {
            url.searchParams.append('search', search);
        }

        const response = await fetch(url);
        const coaches = await response.json();
        return coaches;
    }

    /**
     * Renders the list of coaches in the DOM.
     *
     * @param {Array} coaches - An array of coach objects to be displayed.
     */
    function renderCoaches(coaches) {
        coachList.innerHTML = '';
        coaches.forEach(coach => {
            const coachElement = document.createElement('div');
            coachElement.className = 'coach';
            coachElement.innerHTML = `
                <h2>${coach.name}</h2>
                <p>Experience: ${coach.experience} years</p>
                <p>Rate: $${coach.rate}/hour</p>
                <p>Location: ${coach.location}</p>
                <p>Joined: ${coach.joined}</p>
            `;
            coachList.appendChild(coachElement);
        });
    }

    /**
     * Filters and sorts the coaches based on user input.
     */
    async function filterAndSortCoaches() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortValue = sortSelect.value;
        const coaches = await fetchCoaches(searchTerm, sortValue);
        renderCoaches(coaches);
    }

    searchInput.addEventListener('input', filterAndSortCoaches);
    sortSelect.addEventListener('change', filterAndSortCoaches);

    filterAndSortCoaches();
});