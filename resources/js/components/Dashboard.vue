<template>
  <div>
    <div class="all_container">
      <div class="top">
        <h3>{{ workspace.name }}</h3>
        <div class="hr"></div>
        
        <div class="board">
          <div class="title">
            <p>Tableaux</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"/></svg>
          </div>

          <ul>
            <li v-for="item in workspace.boards" :key="item.name">
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
  </div>
</template>

<script>
  export default {
    data() {
      return {
        workspace: {
          name: "Test",
          boards: [
            {name: "Front-End"},
            {name: "Back-End"},
          ],
        }
      }
    },

  }
</script>

<style lang="scss" scoped>
  @import "../../sass/app.scss";

  .all_container {
    max-width: 15rem;
    // overflow-y: scroll;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    .top {
      h3 {
        margin-block: 2rem;
        font-size: 1.5rem;
        text-align: center;
      }

      .board {
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
</style>