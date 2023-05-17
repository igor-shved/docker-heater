import {createApp} from "vue";

const app = createApp({});
app.component('debug_list', require('../components/debug/DebugList.vue').default);

//enable error handler
//app.config.warnHandler = null;

//disable error handler
//app.config.silent = true;
app.config.warnHandler = (msg, vm, trace) => {
    console.log('handler error - ' + msg);
    if (msg.startsWith('Failed to resolve')) {
        return;
    }
    console.log(msg, vm, trace);
}

app.mount('#debug');
