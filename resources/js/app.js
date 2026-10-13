import '../css/master.css';
// import '../scss/app.scss';
import './bootstrap'; // lascia se esiste bootstrap.js; altrimenti rimuovi
import Slider from './modules/Slider';
import Menu from './modules/Menu';
// import Animations from './modules/Animations';
import MenuItem from './modules/MenuItem';
import NewsModal from './modules/NewsModal';
import ImageNavigation from './modules/ImageNavigation';
import Nav from './modules/Nav';
import Accordion from './modules/Accordion';
import PhotoGallery from './modules/PhotoGallery';
import WordsSplitter from './modules/WordsSplitter';
import LettersSplitter from './modules/LettersSplitter';
import LettersAnimation from './modules/LettersAnimation';
console.log('Vite assets loaded');


document.addEventListener('DOMContentLoaded', () => {
	Accordion.init();
	Slider.init();
	Menu.init();
	// Animations.init();
	MenuItem.init();
	NewsModal.init();
	ImageNavigation.init();
	Nav.init();
	PhotoGallery.init();
	WordsSplitter.init();
	LettersSplitter.init();
	LettersAnimation.init();
});