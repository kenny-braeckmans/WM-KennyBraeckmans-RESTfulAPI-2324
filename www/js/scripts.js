/**
 * Exercise: RESTful API
 * 
 * @description CHANGEME
 * @author Kenny Braeckmans <kenny.braeckmans@student.odisee.be>
 */

const debug = true;

const baseUrl = 'https://api.bennykraeckmans.be';
const endpoints = {
    projects: '/v1/projects',
    employees: '/v1/employees'
};

async function fetchData(url) {
    if (debug) console.log(`[+] fetchData(${url})`);

    try {
        const response = await fetch(url);
        const data = await response.json();
        if (debug) console.table(data);
        return data;
    } catch (error) {
        console.error(error);
    }
}

function generateTable(headers, json, element) {
    if (debug) console.log('[+] generateTable');

    const table = document.createElement('table');
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');
    const headerRow = document.createElement('tr');

    headers.forEach(header => {
        const th = document.createElement('th');
        th.innerText = header;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);

    json.forEach(project => {
        const tr = document.createElement('tr');

        for (const [key, value] of Object.entries(project)) { // FIX ME, ugly...
            const td = document.createElement('td');
            td.innerText = value;
            tr.appendChild(td);
        }

        tbody.appendChild(tr);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    table.classList.add('table', 'table-striped');

    element.innerHTML = '';
    element.appendChild(table);
}

document.querySelector('#btnProjects').addEventListener('click', async (event) => {
    event.preventDefault();

    if (debug) console.log('[+] btnFetchProjects clicked');

    try {
        const projects = await fetchData(baseUrl + endpoints.projects);
        generateTable(
            ['Id', 'Name', 'Code', 'Description'],
            projects,
            document.querySelector('#table'));
    } catch (error) {
        console.error("[!] Error fetching and displaying projects.", error);
    }
});

document.querySelector('#btnEmployees').addEventListener('click', async (event) => {
    event.preventDefault();

    if (debug) console.log('[+] btnFetchEmployees clicked');

    try {
        const employees = await fetchData(baseUrl + endpoints.employees);
        generateTable(
            ['Id', 'First name', 'Last name', 'Specialization'],
            employees,
            document.querySelector('#table'));
    } catch (error) {
        console.error("[!] Error fetching and displaying employees.", error);
    }
});

window.addEventListener('load', async () => {
    if (debug) console.log('😺');

    try {
        const projects = await fetchData(baseUrl + endpoints.projects);
        generateTable(
            ['Id', 'Name', 'Code', 'Description'],
            projects,
            document.querySelector('#table'));
    } catch (error) {
        console.error("[!] Error fetching and displaying projects.", error);
    }
});