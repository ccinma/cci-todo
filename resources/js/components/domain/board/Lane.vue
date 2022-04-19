<template>

  <div class="lane">

    <div class="lane-header">
      <h3 class="lane-header-title" v-if="!modifyName">{{ lane.name }}</h3>
      <form @submit.prevent="send">
        <td-input-text :id="'name-input-'+ lane.id" class="name-input" name="name" :value="lane.name" v-if="modifyName" :blur="send" />
      </form>
      <div class="lane-header-menu">
        <div class="lane-header-menu-backdrop" v-if="isOpen" v-on:click.prevent.stop="toggleDropdown"></div>
        <div class="lane-header-menu-btn" v-on:click.stop="toggleDropdown">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
        </div>
        <div class="lane-header-menu-dropdown" v-if="isOpen">
          <div class="dropdown-close">
            <div v-on:click.stop="toggleDropdown">
              &times;
            </div>
          </div>
          <content-divider />
          <ul class="dropdown-list">
            <li v-on:click.prevent="openModifyName">
              Modifier le nom
            </li>
            
            <content-divider />

            <li v-if="!confirmDeleteLane" v-on:click.prevent="openConfirmation" class="dropdown-element">
              Supprimer la liste
            </li>
            <li v-if="confirmDeleteLane" v-on:click.prevent="deleteLane" class="dropdown-element danger">
              Confirmer suppression ?
            </li>
          </ul>
        </div>
      </div>
    </div>

    <button class="todo-btn-round btn">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>
      </div>
    </button>

  </div>

</template>

<script>
  import TdInputText from '../../UI/TdInputText.vue'

  export default {

    props: ['lane'],

    components: {TdInputText},

    data() {
      return {
        isOpen: false,
        confirmDeleteLane: false,
        modifyName: false,
      }
    },

    methods :{
      toggleDropdown() {
        this.confirmDeleteLane = false
        this.isOpen = ! this.isOpen
      },
      openConfirmation() {
        this.confirmDeleteLane = true
      },
      async deleteLane() {
        this.toggleDropdown()
        await this.$store.dispatch('deleteLane', {lane_id: this.lane.id})
      },
      openModifyName(){
        this.modifyName = true  
        this.toggleDropdown()
        setTimeout(() => {
          const input = document.querySelector('#name-input-'+ this.lane.id)
          const end = input.value.length;
          input.setSelectionRange(end, end);
          input.focus()
        }, 50)
      },
      send() {
        const input = document.querySelector('#name-input-'+ this.lane.id)
        const newName = input.value
        const oldName = this.lane.name
        if (newName !== oldName) {
          this.$store.dispatch('editLane', {
            lane_id: this.lane.id,
            name: newName
          })
        }
        this.modifyName = false
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "resources/sass/_breakpoints.scss";
  @import "resources/sass/_variables.scss";
  @import "resources/sass/_colors.scss";

  .lane {
    padding: 1rem;

    color: $white;
    background: $royalbluedark;

    border-radius: 1rem;

    display: flex;
    flex-direction: column;

    .name-input {
      margin-right: 0.75rem;
    }

    &-header {

      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;

      &-title {
        font-size: 1.2rem;
        user-select: none;
      }

      &-menu {

        position: relative;

        &-backdrop {
          position: fixed;
          left: 0;
          right: 0;
          bottom: 0;
          top: 0;
          z-index: 1;
        }
        
        &-btn {
          display: flex;
          justify-content: center;
          gap: 2px;
          height: 100%;
          cursor: pointer;
        }

        &-dropdown {

          z-index: 2;

          padding-top: 0.25rem;
          padding-inline: 0.75rem;
          padding-bottom: 1rem;

          position: absolute;
          top: 29px;
          left: 0;
          
          overflow: hidden;

          white-space: nowrap;
          color: $royalbluedark;
          background-color: rgba(255, 255, 255, 0.918);
          backdrop-filter: blur(5px);
          border-radius: 5px;
          border: 1px solid #00000040;
        }

        .dot {
          height: 0.5rem;
          width: 0.5rem;
          border-radius: 9999px;
          background-color: white;
          margin-top: 0.5rem;
        }
      }
    }


    .dropdown {
      &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 1.5rem;
        &>* {
          cursor: pointer;
        }
      }
      &-element {
        cursor: pointer;
        &.danger {
          color: rgb(178, 7, 7);
          font-size: bold;
        }
      }
    }


    .btn {
      width: auto;

      padding: 0.75rem;

      background: $white;
      border: $white;

      align-self: center;

      > div {
        width: 1rem;
        height: 1rem;

        display: flex;
        justify-content: center;
        align-items: center;
      }

      svg {
        fill: $royalbluedark;
      }
    }
  }
</style>
