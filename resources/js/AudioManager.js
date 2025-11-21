export default class AudioManager {
    constructor() {
        this.sounds = {};
        this.muted = false;
        
        // Preload Audio Files
        // Pastikan path-nya sesuai dengan public folder kamu
        this.load('typing', '/audio/typing.mp3');
        this.load('hover', '/audio/hover.wav');
        this.load('process', '/audio/processing.wav'); // Suara loading
        this.load('win', '/audio/win.wav');     // Suara hasil keluar
    }

    load(name, path) {
        this.sounds[name] = new Audio(path);
        this.sounds[name].volume = 0.5; // Set volume default 50%
    }

    play(name) {
        if (this.muted) return;
        
        const sound = this.sounds[name];
        if (sound) {
            sound.currentTime = 0; // Reset ke awal (agar bisa dispam)
            sound.play().catch(e => console.warn("Audio play blocked", e));
        }
    }

    toggleMute() {
        this.muted = !this.muted;
        return this.muted;
    }
}