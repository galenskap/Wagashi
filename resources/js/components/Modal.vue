<template>
    <transition name="fade" appear>
        <div class="modal-back" @click.self="$emit('close')">
            <div class="modal-wrapper">
                <div class="modal-header">
                    <h3>{{title}}</h3>
                    <img class="close" @click="$emit('close')" :src="Cross" alt="Fermer">
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <div class="modal-footer">
                    <button class="button blush" @click="$emit('confirm')">Confirmer</button>
                    <button class="button dark" @click="$emit('close')">Annuler</button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
    import Cross from "../../img/cross.svg";
    const props =  defineProps(['title']);
</script>

<style scoped>
.modal-back {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 10;
}
.modal-wrapper {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80%;
    max-width: 850px;
    background-color: #fff;
    border-radius: 5px;
    padding: 1rem;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.modal-header h3 {
    margin: 0;
    font-size: 1.7rem;
}
.modal-header button {
    border: none;
    background: none;
    cursor: pointer;
}

.close {
    font-size: 2rem;
    width: 1rem;
}

.button {
    margin-bottom: 1rem;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.message {
    font-size: 1.7rem
}
.code {
    padding: 1rem;
    width: 100%;
}
</style>
