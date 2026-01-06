<template>
  <div class="ticket-create-view max-w-container mx-auto">
    <div class="mb-lg">
       <router-link :to="{ name: 'member-tickets' }" class="nav-back">
          <i class="fas fa-arrow-left"></i> Back to List
        </router-link>
      <h2 class="page-title mt-sm">Submit a Request</h2>
    </div>

    <form @submit.prevent="submitTicket" class="card p-lg">
      
      <!-- Category Selection -->
      <div class="form-group">
        <label>Category</label>
        <select v-model="form.category_id" required class="input">
          <option value="" disabled>Select a category</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
      </div>

      <!-- Subject -->
      <div class="form-group">
        <label>Subject</label>
        <input v-model="form.subject" type="text" required placeholder="Brief summary of your issue" class="input">
      </div>

      <!-- Content -->
      <div class="form-group">
         <label>Details</label>
         <textarea v-model="form.content" rows="6" required placeholder="Please describe your issue in detail..." class="input"></textarea>
      </div>

      <!-- Actions -->
      <div class="form-actions mt-lg pt-lg border-t">
        <router-link :to="{ name: 'member-tickets' }" class="btn btn-secondary mr-md">Cancel</router-link>
        <button type="submit" class="btn" :disabled="submitting">
          <span v-if="submitting">Submitting...</span>
          <span v-else>Submit Ticket</span>
        </button>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="text-danger mt-md text-sm">
        {{ error }}
      </div>

    </form>
  </div>
</template>

<style scoped>
.max-w-container {
  max-width: 800px;
}

.mx-auto { margin-left: auto; margin-right: auto; }

.nav-back {
  color: var(--color-text-muted);
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.nav-back:hover { color: var(--color-primary); }

.page-title {
  margin: 0;
  color: var(--color-text-main);
}

.form-group {
  margin-bottom: var(--spacing-md);
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.border-t {
  border-top: 1px solid var(--color-border);
}

.mr-md { margin-right: var(--spacing-md); }
.mt-sm { margin-top: var(--spacing-sm); }
.mt-lg { margin-top: var(--spacing-lg); }
.pt-lg { padding-top: var(--spacing-lg); }
.text-danger { color: var(--color-danger); }
.text-sm { font-size: 0.875rem; }
</style>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const categories = ref([]);
const submitting = ref(false);
const error = ref(null);

const form = reactive({
  category_id: '',
  subject: '',
  content: ''
});

const fetchCategories = async () => {
  try {
    // Assuming we can use the same category API, or maybe we should filter by type if distinction exists.
    // For now, using standard categories list.
    const response = await axios.get('/api/categories');
    categories.value = response.data;
  } catch (err) {
    console.error('Failed to load categories', err);
  }
};

const submitTicket = async () => {
  submitting.value = true;
  error.value = null;

  try {
    await axios.post('/api/tickets', form);
    router.push({ name: 'member-tickets' });
  } catch (err) {
    if (err.response && err.response.status === 422) {
      error.value = 'Please check your inputs.';
    } else {
      error.value = 'An error occurred. Please try again later.';
    }
    console.error(err);
  } finally {
    submitting.value = false;
  }
};

onMounted(() => {
  fetchCategories();
});
</script>
