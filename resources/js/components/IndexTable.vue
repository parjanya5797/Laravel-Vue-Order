<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const orders = ref([])
const currentPage = ref(1)
const itemsPerPage = ref(10)
const orderNumber = ref('')
const status = ref('')
const dateCreated = ref('')
const orderStatuses = ref([]) 
const sortField = ref('')
const sortOrder = ref('asc')
const totalOrders = ref(0)
const toast = useToast()

const fetchFilteredOrders = async () => {
  try {
    const response = await axios.get('/api/order', {
      params: {
        page: currentPage.value,
        orderNumber: orderNumber.value,
        status: status.value,
        dateCreated: dateCreated.value,
        sortField: sortField.value,
        sortOrder: sortOrder.value
      }
    })
    orders.value = response.data.data
    itemsPerPage.value = response.data.per_page
    currentPage.value = response.data.current_page
    totalOrders.value = response.data.total
  } catch (error) {
    console.error('Failed to fetch filtered orders:', error)
    toast.error('Failed to fetch filtered orders: ' + error.message)
  }
}

const fetchOrderStatuses = async () => {
  try {
    const response = await axios.get('/api/order-status')
    orderStatuses.value = response.data
  } catch (error) {
    console.error('Failed to fetch order statuses:', error)
  }
}

const syncOrders = async () => {
  try {
    const response = await axios.get('/api/sync-orders')
    toast.success(response.data.message)
    fetchFilteredOrders()
  } catch (error) {
    console.error('Failed to sync orders:', error)
    toast.error('Failed to sync orders.')
  }
}

onMounted(() => {
  fetchFilteredOrders()
  fetchOrderStatuses()
})

watch([orderNumber, status, dateCreated, sortField, sortOrder, currentPage], () => {
  fetchFilteredOrders()
})
</script>

<template>
  <div class="table-responsive">
    <div class="d-flex justify-content-between mb-3">
      <input v-model="orderNumber" type="text" placeholder="Search by Order Number" class="form-control" @input="fetchFilteredOrders">
      <select v-model="status" class="form-control" @change="fetchFilteredOrders">
        <option value="">All Statuses</option>
        <option v-for="orderStatus in orderStatuses" :key="orderStatus" :value="orderStatus">{{ orderStatus }}</option>
      </select>
      <input v-model="dateCreated" type="date" placeholder="Filter by Date Created" class="form-control" @change="fetchFilteredOrders">
      <select v-model="sortField" class="form-control" @change="fetchFilteredOrders">
        <option value="">Sort By</option>
        <option value="number">Order Number</option>
        <option value="status">Status</option>
        <option value="total">Total</option>
        <option value="date_created">Date Created</option>
      </select>
      <select v-model="sortOrder" class="form-control" @change="fetchFilteredOrders">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>
    </div>
    <div class="d-flex justify-content-end">
      <button @click="syncOrders" class="btn btn-primary">Sync Orders</button>
    </div>
    <table class="table table-striped table-hover table-bordered">
      <thead class="table-light">
        <tr>
          <th scope="col" class="text-start">
            Order Number
          </th>
          <th scope="col" class="text-start">
            Status
          </th>
          <th scope="col" class="text-start">
            Total
          </th>
          <th scope="col" class="text-start">
            Date Created
          </th>
          <th scope="col" class="text-start">
            Customer Name
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="orders.length === 0">
          <td colspan="5" class="text-center">No Records found</td>
        </tr>
        <tr v-else v-for="order in orders" :key="order.id">
          <td class="text-start">{{ order.number }}</td>
          <td class="text-start"><b>{{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}</b></td>
          <td class="text-start">{{ order.total }}</td>
          <td class="text-start">{{ order.date_created }}</td>
          <td class="text-start">{{ JSON.parse(order.billing)?.first_name }} {{ JSON.parse(order.billing)?.last_name }}</td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-between">
      <button class="btn btn-secondary" :disabled="currentPage === 1" @click="currentPage = Math.max(currentPage - 1, 1)">Previous</button>
      <span>
        Page {{ currentPage }} of {{ Math.ceil(totalOrders / itemsPerPage) }}
      </span>
      <button class="btn btn-secondary" :disabled="currentPage * itemsPerPage >= totalOrders" @click="currentPage = Math.min(currentPage + 1, Math.ceil(totalOrders / itemsPerPage))">Next</button>
    </div>
  </div>
</template>
