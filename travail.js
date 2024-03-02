 
const  titreSpans =document.querySelectorAll('h2 span');
const medias=document.querySelectorAll('.ki');
const l1=document.querySelector('.l1');
const l2=document.querySelector('.l2');
const l3=document.querySelector('.l3');
const l4=document.querySelector('.l4');


window.addEventListener('load',()=>{

 const TL =gsap.timeline({paused: true});

 TL 
    .staggerFrom(titreSpans ,1, {top:-50, opacity:0, ease: "power2.out"},0.4)
    .from(l1, 1.5 , {width:0,ease:"power2.out"}, '-=3')
    .from(l2, 1 , {width:0,ease:"power2.out"}, '-=2')
    .from(l3, 1 , {width:0,ease:"power2.out"}, '-=2')
    .from(l4, 1.5 , {width:0,ease:"power2.out"}, '-=3')
    .staggerFrom(medias ,1, {right: -200 , ease: "power2.out"},0.3,'-=1');
  
  
  TL.play();


})

