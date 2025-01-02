<template>
    <button class="badge-light-primary btn-block btn-mail w-100" type="button" data-bs-toggle="modal"
        data-bs-target="#exampleModal">Add
    </button>
    <div class="modal fade modal-bookmark" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <form class="form-bookmark needs-validation" id="bookmark-form" novalidate="">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <input class="form-control" type="text" placeholder="Company">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input class="form-control" type="text" placeholder="Username">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input class="form-control" type="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input class="form-control" type="text" placeholder="Company">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input class="form-control" type="text" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input class="form-control" type="text" placeholder="Home Address">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input class="form-control" type="text" placeholder="City">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input class="form-control" type="number" placeholder="ZIP Code">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label">Country</label>
                                    <select class="form-control btn-square">
                                        <option value="0">--Select--</option>
                                        <option value="1">Germany</option>
                                        <option value="2">Canada</option>
                                        <option value="3">Usa</option>
                                        <option value="4">Aus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div>
                                    <label class="form-label">About Me</label>
                                    <textarea class="form-control" rows="5" placeholder="Enter About your description"></textarea>
                                </div>
                            </div>
                        </div>
                        <input id="index_var" type="hidden" value="6">
                        <button class="btn btn-secondary" type="submit" id="Bookmark"
                            @click="submitBookmark()">Save</button>
                        <button class="btn btn-primary ms-2" type="button" data-bs-dismiss="modal">Cancel </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            name: '',
            type: '',
            desc: ''
        }
    },
    methods: {
        submitBookmark() {
            let arr = {
                name: this.name,
                type: this.type,
                desc: this.desc
            }
            this.$store.commit('tags/add', arr)
        }

    },
}
</script>
