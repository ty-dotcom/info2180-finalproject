// function handleLookup(){

//     const countrylookupBtn = document.querySelector('#lookup');
//     const citylookupBtn = document.querySelector('#lookup-cities');
//     const result = document.querySelector('#result');
//     const input = document.querySelector('#country');

//     countrylookupBtn.addEventListener('click', (event)=>{
//         event.preventDefault();        
//         fetchUsers();
//     });
// }

function fetchUsers(){
    const url = 'dolphin_crm.php?context=users';
    const container = document.querySelector('.table-container.users');
    fetch(url)
        .then(res => {
            if (!res.ok){
                throw new Error("Users not found");
            }
            return res.text();
        })
        .then(data => {
            container.innerHTML = data;
        })
        .catch(() => {
            container.innerHTML = "<h4 class='not-found'> USERS NOT FOUND. <h4>";
        });
}

window.onload = function(){
    fetchUsers();
}
