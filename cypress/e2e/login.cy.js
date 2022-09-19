describe('The user login', () => {
  it('successfully logs the user', () => {
    cy.visit('http://localhost:1000/login')
    cy.get('input[name=email]').type('mire@gmail.com')
    cy.get('input[name=password]').type('orioloriol')
    cy.contains('Access').click()
    cy.get('h1[class=m-0]').should('be.visible')
  })
})