import { styled } from '@linaria/react';
import React from 'react';

const Container = styled.div`
  display: flex;
  justify-content: center;
  padding-bottom: 8px;
`;

export default function ElementorButton({ children, ...params }) {
  return (
    <Container className="elementor-button-wrapper">
      <button
        className="elementor-button elementor-button-default"
        type="button"
        {...params}
      >
        {children}
      </button>
    </Container>
  );
}
