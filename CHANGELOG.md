# CHANGELOG

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2025-01-03

### Added
- **Superadmin Module:**
  - Integrated theme for Super Admin dashboard.
  - Login, logout, and session maintenance with token-based authentication.
  - Profile management with the ability to update name, email, and password.
  - CRUD operations for client management (listing, adding, editing, and removing clients).

- **Client Module:**
  - Integrated theme for Client dashboard.
  - Login, logout, and session maintenance.
  - Profile management functionality.
  - User management with the ability to list, add, edit, and remove users.

- **API Development:**
  - Secure API endpoints for Super Admin and Client modules.
  - Role-based authentication for all API endpoints.
  - CRUD operations for user and client management.

- **Frontend (VueJS):**
  - Responsive and reusable components for both dashboards.
  - Dynamic forms with client-side validation.
  - State management using Vuex.
  - Routing and navigation for both modules.

- **Backend (Laravel):**
  - Database schema design for users, roles, permissions, and clients.
  - API token-based authentication.
  - Error handling and validation for all endpoints.

### Fixed
- UI alignment issues in mobile view for both Super Admin and Client dashboards.

### Notes
- This is the first version of the project, ready for deployment.

---

### Release Highlights
This release marks the launch of the Template Module, providing foundational functionality for Super Admin and Client users. The application integrates Laravel and VueJS to deliver a responsive and robust experience.
