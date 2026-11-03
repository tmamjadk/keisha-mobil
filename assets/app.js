document.addEventListener('DOMContentLoaded',function(){
 const search=document.querySelector('[data-car-search]');
 const items=[...document.querySelectorAll('[data-car-item]')];
 const empty=document.querySelector('[data-empty-results]');
 if(search){search.addEventListener('input',function(){const q=this.value.toLowerCase().trim();let count=0;items.forEach(el=>{const show=(el.dataset.search||el.innerText).toLowerCase().includes(q);el.style.display=show?'':'none';if(show)count++;});if(empty)empty.style.display=count?'none':'';});}
});
