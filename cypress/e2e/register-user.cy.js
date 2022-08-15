describe('Calendar creation', () => {
    it('is successfully created', () => {
        cy.visit('http://localhost:1000/login')
        cy.get('input[name=email]').type('mire@gmail.com')
        cy.get('input[name=password]').type('orioloriol')
        cy.contains('Access').click()

        cy.get('a[class=small-box-footer]').eq(1).click()
        cy.get('form[name=user_registration]').should('be.visible')

        cy.get('input[id=user_registration_name]').type('Luis')
        cy.get('input[id=user_registration_lastname]').type('Suarez')
        cy.get('input[id=email]').type('luissuarez@gmail.com')
        cy.get('input[id=user_registration_direction]').type('Terrassa')
        cy.get('input[id=user_registration_city]').type('Roberto')
        cy.get('input[id=user_registration_province]').type('Roberto')
        cy.get('input[id=postalCode]').type('08222')

        cy.get('select[id=role]').select('Admin').should('have.value', 'ROLE_ADMIN')
        cy.get('select[id=department]').select('Departamento de admin').should('have.value', '1')

        cy.get('button[id=user_registration_submit]').click()
    })
})