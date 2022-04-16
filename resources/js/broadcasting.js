export function connectGeneral($id) {

    window.Echo.private(`general-${$id}`)
    .listen('GeneralBroadcastQuestion', (e) => {
        console.log(e);
        console.log('sd');
    });

}


export function connectPlayer($id) {

    window.Echo.private(`player-${$id}`)
    .listen('PlayerCards', (e) => {
        console.log(e);
        console.log('sd');
    });

}
