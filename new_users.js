function submitFormData(){
    const form = document.querySelector('form');
    form.addEventListener('submit', (event)=>{
        event.preventDefault();
        
        const formInput = new FormData(form);
        const data = Object.fromEntries(formInput.entries());
        const sanitized = {};

        for (const [key, value] of Object.entries(data)){
            sanitized[key] = value.trim();
        };
        
        addNewUser(sanitized);
    })
}

function addNewUser(user){
    const url = `dolphin_crm.php?context=add_user`;
    const container = document.querySelector('.results');
    if (container.classList.contains('success') || container.classList.contains('error')){
        container.classList.remove('success');
        container.classList.remove('error');
    };
    fetch(url, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify(user)
    })
        .then(res => {
            if (!res.ok){
                            console.log(res)

                throw new Error("Fail to add User.");
            }
            return res.text();
        })
        .then(text => {
            console.log(text)
            return JSON.parse(text);
        })
        .then((result) => {
            container.classList.add('success');
            container.innerHTML = result.message;
        })
        .catch((err) => {
            container.classList.add('error');
            container.innerHTML = err.message;
        });
}

window.onload = function(){
    submitFormData();
}