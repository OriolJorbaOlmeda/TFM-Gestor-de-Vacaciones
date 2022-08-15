describe('Calendar creation', () => {
    it('is successfully created', () => {
        cy.visit('http://localhost:1000/login')
        cy.get('input[name=email]').type('mire@gmail.com')
        cy.get('input[name=password]').type('orioloriol')
        cy.contains('Access').click()
        cy.contains('Go').first().click()

        cy.get('select[id=calendar_year]').select('2023').should('have.value', '2023')
        cy.get('input[id=fechaInicio]').type('2023-01-01')
        cy.get('input[id=fechaFin]').type('2023-12-31')
        cy.get('button[id=calendar_createCalendar]').click()

        cy.contains('Save').click()
        cy.get('h1[class=m-0]').should('be.visible')
    })
})