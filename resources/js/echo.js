import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '1b9df3d1771c72bbcc3d',
    cluster: 'eu',
    encrypted: true
});
