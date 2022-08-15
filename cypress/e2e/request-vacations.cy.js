describe('The request vacation', () => {
    it('successfully requested', () => {
        cy.visit('http://localhost:1000/login')
        cy.get('input[name=email]').type('jose@gmail.com')
        cy.get('input[name=password]').type('orioloriol')
        cy.contains('Access').click()
        cy.get('h1[class=m-0]').should('be.visible')

        cy.contains('Request vacations ').click()
        cy.get('input[id=fechaInicio]').type('2022-11-01')
        cy.get('input[id=fechaFin]').type('2022-11-15')
        cy.get('textarea[id=request_vacation_form_reason]').type('Holidays')
        cy.get('button[id=request_vacation_form_save]').click()
    })
})