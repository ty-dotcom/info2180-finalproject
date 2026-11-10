
function assignFilters(){
    
    const btns = document.querySelectorAll('.buttons button');
    btns.forEach((btn)=>{
        btn.addEventListener('click', (event)=>{
            event.preventDefault();
            const activeBtn = document.querySelector('.buttons .is-active');
            activeBtn.classList.remove('is-active');
            btn.classList.toggle('is-active');
            fetchContacts();
            
        })
    })
}

function getFilter(){
    const activeBtn = document.querySelector('.is-active');
    let filter = activeBtn.innerHTML;
    return filter;
}

function fetchContacts(){
    const filter = getFilter();
    const url = `dolphin_crm.php?context=contacts&filter=${encodeURIComponent(filter)}`;
    const container = document.querySelector('.table-container.dashboard');
    fetch(url)
        .then(res => {
            if (!res.ok){
                throw new Error("Contacts not found");
            }
            return res.text();
        })
        .then(data => {
            container.innerHTML = data;
        })
        .catch(() => {
            container.innerHTML = "<h4 class='not-found'> CONTACTS NOT FOUND. <h4>";
        });
}


window.onload = function(){
    assignFilters();
    fetchContacts();
}