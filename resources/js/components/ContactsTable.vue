<template>
  <table class="contacts-table">
    <thead>
      <tr>
        <th><input type="checkbox" @click="toggleCheckboxes" /></th>
        <th>Name</th>
        <th>Email</th>
        <th>Account</th>
        <th>Phone number</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="contact in contacts" :key="contact.hash">
        <td><input type="checkbox" :checked="checkboxesToggled"/></td>
        <td>
          <template v-if="hasName(contact)">
            <span class="contact-gravatar">{{contactInitials(contact)}}</span>
            <a href="#">{{contactName(contact)}}</a>
          </template>
          <template v-else>
            <div class="default-gravatar">
              <img src="https://d226aj4ao1t61q.cloudfront.net/gjcq9h7qt_gravatar_camp_default_circle.png"/>
              <span>--</span>
            </div>
          </template>
        </td>
        <td><a href="#">{{valueOrDash(contact.email)}}</a></td>
        <td>{{contactAccount(contact)}}</td>
        <td>{{formatPhone(contact.phone)}}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import axios from 'axios'

export default {

  data() {
    return {
      checkboxesToggled: false,
      contacts: [],
      accounts: {},
    }
  },

  mounted() {
    axios.get('/api/contacts').then((data) => {
      this.contacts = data.data.contacts
      let accounts = {}
      data.data.accounts.forEach(function(account) {
        accounts[account.id] = account
      })
      this.accounts = accounts
    })
  },

  methods: {
    hasName(contact) {
      return contact.firstName && contact.lastName
    },
    contactName(contact) {
      if (contact.firstName) {
        return `${contact.firstName} ${contact.lastName}`
      } else {
        return this.valueOrDash(null)
      }
    },
    valueOrDash(value) {
      return value ? value : '--'
    },
    contactInitials(contact) {
      return contact.firstName[0] + contact.lastName[0]
    },
    contactAccount(contact) {
      let accountName = this.accounts[contact.accountContacts[0]]?.name
      return this.valueOrDash(accountName)
    },
    toggleCheckboxes() {
      this.checkboxesToggled = !this.checkboxesToggled
    },
    formatPhone(number) {
      let formatted = ""
      if (number) {
        let clean = number.replace(/[\.\-\(\)]/g, '')
        formatted = `(${clean.substring(0,3)}) ${clean.substring(3,6)}-${clean.substring(6)}`
      }
      return this.valueOrDash(formatted)
    }
  },

}
</script>

<style lang="scss">
#app {
  height: 100%;
  display: flex;
  justify-content: center;
  font-family: "IBM Plex Sans", sans-serif;
  color: #666;
}

a {
  color: #356AE6;
  text-decoration: none;
}

.contacts-table {
  width: 70%;
  border-spacing: 0;
  color: inherit;

  th, td {
    text-align: left;
    border-bottom: 1px solid #ccc;
  }
  th:first-child, td:first-child {
    border-left: 1px solid #ccc;
  }
  th:last-child, td:last-child {
    border-right: 1px solid #ccc;
  }

  th {
    border-top: 1px solid #ccc;
    padding: 5px;
  }

  td {
    padding: 15px 5px;
  }

  tr {

    &:hover {
      background: #fafbff;
    }
  }

  .contact-gravatar {
    background: #eae0fe;
    border-radius: 100px;
    display: inline-flex;
    height: 30px;
    justify-content: center;
    align-items: center;
  }

  .default-gravatar {
    display: flex;
    align-items: center;

    span {
      margin-left: 5px;
    }
  }

  .default-gravatar img, .contact-gravatar {
    width: 30px;
  }
}
</style>