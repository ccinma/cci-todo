<template>
  <div id="dashboard">
    <div class="all_container left" v-bind:class="{ active: leftIsOpen, 'all_container left-close': !leftIsOpen }">
      <div class="top">
        <h3>{{ workspace.name }}</h3>
        <div class="hr"></div>
        
        <div class="board">
          <div class="title" v-on:click="createBoardVisibility = true">
            <p>Tableaux</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>
          </div>

          <ul>
            <li v-for="(item, index) in workspace.boards" v-on:click="changeBoard(index)">
              {{ item.name }}
            </li>
          </ul>
        </div>

        <div class="hr"></div>

        <div class="users">
          <div class="title" v-on:click="addUserVisibility = true">
            <p>Participants</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>
          </div>

          <ul>
            <li v-for="item in workspace.users" :key="item.name">
              {{ item.name }}
            </li>
          </ul>
        </div>
      </div>
      
      <div class="bottom">
        <div class="hr"></div>
        <div class="change">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M32 176h370.8l-57.38 57.38c-12.5 12.5-12.5 32.75 0 45.25C351.6 284.9 359.8 288 368 288s16.38-3.125 22.62-9.375l112-112c12.5-12.5 12.5-32.75 0-45.25l-112-112c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L402.8 112H32c-17.69 0-32 14.31-32 32S14.31 176 32 176zM480 336H109.3l57.38-57.38c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0l-112 112c-12.5 12.5-12.5 32.75 0 45.25l112 112C127.6 508.9 135.8 512 144 512s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L109.3 400H480c17.69 0 32-14.31 32-32S497.7 336 480 336z"/></svg>
          <p>Changer d'espace de travail</p>
        </div>
      </div>
    </div>
    
    <div class="all_container right">
      <button class="todo-btn-secondary-round btn-abs" v-bind:class="{ active: leftIsOpen, 'btn-flip': !leftIsOpen }" v-on:click="changeLeft">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z"/></svg>      
        </div>
      </button>

      <div v-for="(item, index) in workspace.boards[this.currentBoard].lanes" class="lane">
        <Lane :name="item.name" :cards="item.cards" :index="index" @create="catchCreateCard"></Lane>
      </div>

      <button class="todo-btn-secondary-round btn" v-on:click="createLaneVisibility=true">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>          
        </div>
      </button>
    </div>

  <AddUser v-if="addUserVisibility" class="edit" @create="addUser"/>
  <CreateBoard v-if="createBoardVisibility" class="edit" @create="createBoard"/>
  <CreateLane v-if="createLaneVisibility" class="edit" @create="createLane"/>
  <CreateCard v-if="createCardVisibility" class="edit" @create="createCard"/>
  
  </div>
</template>

<script>
  import Lane from './Lane.vue'
  import AddUser from './AddUser.vue'
  import CreateBoard from './CreateBoard.vue'
  import CreateLane from './CreateLane.vue'
  import CreateCard from './CreateCard.vue'

  export default {
    components: {
      Lane,
      AddUser,
      CreateBoard,
      CreateLane,
      CreateCard,
    },

    data() {
      return {
        workspace: {
          name: "Test",
          boards: [
            {name: "Front-End",
              lanes: [
                {name: "Backlog",
                  cards: [
                    {name: "Faire le responsive",
                    labels: [
                      {name: "Front", color: "#FF0000"},
                    ],
                    description: "Ceci est une description random"},
                    {name: "Ajouter un mode sombre",
                    labels: [
                      {name: "Front", color: "#FF0000"},
                    ],
                    description: "Ceci est une description random"},
                    {name: "Finir les maquettes",
                    labels: [
                      {name: "Front", color: "#FF0000"},
                    ],
                    description: "Ceci est une description random"},
                  ]
                },
                {name: "A faire",
                  cards: [],
                },
                {name: "Retour en dev",
                  cards: [],
                },
              ]
            },
            {name: "Back-End",
            lanes: []},
          ],
          users: [
            {name: "Pierre"},
            {name: "Paul"},
            {name: "Jack"},
          ]
        },

        currentBoard: 0,
        currentLane: 0,

        addUserVisibility: false,
        createBoardVisibility: false,
        createLaneVisibility: false,
        createCardVisibility: false,

        leftIsOpen: true,
      }
    },

    methods: {
      changeLeft(event) {
        if (this.leftIsOpen) {
          this.leftIsOpen = false;
        }
        else {
          this.leftIsOpen = true;
        }
      },

      addUser(name) {
        var newUser = {
          name: name,
        }

        if (name != "") {
          this.workspace.users.push(newUser);
        }

        this.addUserVisibility = false;
      },

      createBoard(name) {
        var newBoard = {
          name: name,
          lanes: [],
        }

        if (name != "") {
          this.workspace.boards.push(newBoard);
        }
        this.createBoardVisibility = false;
      },

      createLane(name) {
        var newLane = {
          name: name,
          cards: [],
        }

        if (name != "") {
          this.workspace.boards[this.currentBoard].lanes.push(newLane);
        }
        this.createLaneVisibility = false;        
      },

      catchCreateCard(index)
      {
        this.currentLane = index;
        this.createCardVisibility = true;
      },

      createCard(name, labels, description) {
        var newCard = {
          name: name,
          labels: labels,
          description: description,
        }
        if (name != "") {
          this.workspace.boards[this.currentBoard].lanes[this.currentLane].cards.push(newCard);
        }
        this.createCardVisibility = false;
      },

      changeBoard(index) {
        this.currentBoard = index;
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/app.scss";

  #dashboard {
    display: flex;
    // flex-direction: row;

    .all_container {
      z-index: 0;
    }

    .left {
      max-width: 15rem;
      margin-right: 2rem;

      display: flex;
      flex-direction: column;
      justify-content: space-between;

      li {
        &:hover {
          text-decoration: underline;
          cursor: pointer;
        }
      }

      .top {
        h3 {
          margin-block: 2rem;
          font-size: 1.5rem;
          text-align: center;
        }

        .board, .users {
          margin-block: 2rem;

          .title {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;

            p {
              font-weight: bold;
              font-size: 1.2rem;
            }

            svg {
              height: 1rem;
              fill: $font-color-primary;
            }

            &:hover {
              cursor: pointer;
              p {
                text-decoration: underline;
              }
            }
          }
        }
      }

      .bottom {
        .change {
          margin-top: 2rem;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          align-items: center;

          svg {
            height: 1rem;
            margin-right: 1rem;
            fill: $font-color-primary;
          }
        }
      }

      @include tablet {
        h3 {
          font-size: 2rem;
        }
      }
    }

    .left-close {
      @extend .left;
      width: 0;
      margin-right: 0;
      padding: 0;

      * {
        display: none;
      }
    }

    .right {
      width: 100%;

      .lane {
        width: 15rem;
        margin-top: 2rem;
        margin-right: 1rem;
        
        display: inline-block;
      }

      .btn {
        display: inline-block;
        width: auto;

        padding: 0.75rem;

        > div {
          width: 1rem;
          height: 1rem;

          display: flex;
          justify-content: center;
          align-items: center;
        }
      }

      .btn-abs {
        @extend .btn;

        position: absolute;
        left: 0;
        bottom: 0;

        margin: 2rem;
      }

      .btn-flip {
        @extend .btn-abs;
        transform: rotate(0.5turn);
      }
    }

    .edit {
      position: absolute;

      // top: 0;
      // left: 0;
      // right: 0;
      // bottom: 0;

      // margin-left: auto;
      // margin-right: auto;
      // margin-top: auto;
      // margin-bottom: auto;

      z-index: 20
    }
  }
</style>