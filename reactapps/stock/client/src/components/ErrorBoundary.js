import {Component} from 'react'

export default class ErrorBoundary extends Component {
  constructor(props) {
    super(props)
    this.state = {
      hasError : false
    }
  }

  static getDerivedStateFromError() {
    return {hasError : true}
  }
  componentDidCatch(error, errorinfo) {
    console.log(error, errorinfo)
  }

  render() {

        if(this.state.hasError) {
          return <p>Something went wrong</p>
        }
        return this.props.children

  }
}
