/**
 * Exercise: RESTful API
 * 
 * @description This script fetches and displays current weather data for Brussels.
 * @author Kenny Braeckmans <kenny.braeckmans@student.odisee.be>
 */

const baseUrl = 'https://api.bennykraeckmans.be';
const endpoints = {
    projects:  '/v1/projects',
    employees: '/v1/employees'
};

function buildUrl(baseUrl, endpoint, params = {}) {
    let url = baseUrl + endpoint;

    if (Object.keys(params).length > 0) {
        url += '?';

        for (const [key, value] of Object.entries(params)) {
            url += `${key}=${value}&`;
        }

        url = url.slice(0, -1);
    }

    return url;
}

async function fetchProjects() {
    console.log('[+] fetchProjects');
    const url = buildUrl(baseUrl, endpoints.projects);

    try {
        const response = await fetch(url);
        const data = await response.json();
        // console.log(data);
        return data;
    } catch (error) {
        console.error(error);
    }
}

function addProjectsTable(json, element) {
    console.log('[+] addProjectsTable');
    
    const table = document.createElement('table');
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');

    const headers = ['Id', 'Name', 'Code', 'Description'];
    const headerRow = document.createElement('tr');

    headers.forEach(header => {
        const th = document.createElement('th');
        th.innerText = header;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);

    json.forEach(project => {
        const tr = document.createElement('tr');

        for (const [key, value] of Object.entries(project)) {
            const td = document.createElement('td');
            td.innerText = value;
            tr.appendChild(td);
        }

        tbody.appendChild(tr);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    element.appendChild(table);
}

window.addEventListener('load', async () => {
    console.log('😺');

    try {
        const projects = await fetchProjects();
        addProjectsTable(projects, document.querySelector('#container1'));
    } catch (error) {
        console.error("[!] Error fetching and displaying projects.", error);
    }
});